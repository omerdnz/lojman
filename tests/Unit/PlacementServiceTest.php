<?php

namespace Tests\Unit;

use App\Enums\Gender;
use App\Models\Employee;
use App\Models\Floor;
use App\Models\Room;
use App\Services\CapacityService;
use App\Services\PlacementService;
use App\Services\SettingsService;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PlacementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_gender_mismatch_in_occupied_room_is_rejected(): void
    {
        $this->seed();

        $floor = Floor::query()->whereHas('block', fn ($q) => $q->whereIn('code', ['ANA_LOJMAN', 'ERKEK_LOJMAN']))->first();
        $room = Room::query()->create([
            'floor_id' => $floor->id,
            'room_number' => 'TEST-1',
            'capacity' => 4,
            'gender' => Gender::Male,
            'status' => 'available',
        ]);

        $male = Employee::factory()->create([
            'personnel_number' => 'TEST-GENDER-M',
            'gender' => Gender::Male,
        ]);

        $female = Employee::factory()->create([
            'personnel_number' => 'TEST-GENDER-F',
            'gender' => Gender::Female,
        ]);

        $service = app(PlacementService::class);
        $service->assign($male, $room);

        $this->expectException(ValidationException::class);

        $service->assign($female, $room->fresh(['activePlacements.employee', 'floor.block']));
    }

    public function test_capacity_is_read_from_database_not_hardcoded(): void
    {
        $this->seed(SettingSeeder::class);

        $settings = app(SettingsService::class);
        $capacityService = app(CapacityService::class);

        $this->assertSame(4, $capacityService->resolveCapacityForImport(0));
        $this->assertSame(4, $settings->defaultRoomCapacity());
        $this->assertSame(6, $capacityService->resolveCapacityForImport(6));
    }
}
