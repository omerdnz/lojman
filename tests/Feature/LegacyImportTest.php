<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_seed_imports_expected_counts(): void
    {
        $this->seed();

        $this->assertSame(462, Employee::query()->count());
        $this->assertSame(129, Room::query()->count());
        $this->assertDatabaseCount('placements', 22);
    }

    public function test_super_admin_can_access_capacity_settings(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'admin@lojman.local')->first();

        $response = $this->actingAs($user)->get(route('settings.capacity'));

        $response->assertOk();
        $response->assertSee('Varsayılan Oda Kapasitesi');
    }
}
