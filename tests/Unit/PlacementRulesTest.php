<?php

namespace Tests\Unit;

use App\Enums\Gender;
use App\Enums\GenderPolicy;
use App\Models\Block;
use App\Models\Employee;
use App\Models\Floor;
use App\Models\Room;
use App\Support\PlacementRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlacementRulesTest extends TestCase
{
    use RefreshDatabase;

    private Block $mainBlock;

    private Block $girlsBlock;

    private Floor $mainFloor;

    private Floor $girlsFloor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->mainBlock = Block::query()->whereIn('code', ['ANA_LOJMAN', 'ERKEK_LOJMAN'])->first();
        $this->girlsBlock = Block::query()->where('code', 'KIZLAR_LOJMANI')->first();
        $this->mainFloor = Floor::query()->where('block_id', $this->mainBlock->id)->first();
        $this->girlsFloor = Floor::query()->where('block_id', $this->girlsBlock->id)->first();

        $this->assertSame(GenderPolicy::Mixed, $this->mainBlock->gender_policy);
        $this->assertSame(GenderPolicy::Female, $this->girlsBlock->gender_policy);
    }

    public function test_male_labeled_empty_room_allows_female_in_main_block(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $female = Employee::factory()->create(['gender' => Gender::Female]);

        $this->assertTrue(PlacementRules::canPlace($female, $room));
    }

    public function test_male_cannot_be_placed_in_girls_block(): void
    {
        $room = $this->createRoom($this->girlsFloor, Gender::Female);
        $male = Employee::factory()->create(['gender' => Gender::Male]);

        $this->assertFalse(PlacementRules::canPlace($male, $room));
        $this->assertStringContainsString('Kızlar bloğu', PlacementRules::validate($male, $room));
    }

    public function test_female_can_be_placed_in_girls_block(): void
    {
        $room = $this->createRoom($this->girlsFloor, Gender::Female);
        $female = Employee::factory()->create(['gender' => Gender::Female]);

        $this->assertTrue(PlacementRules::canPlace($female, $room));
    }

    public function test_female_can_be_placed_in_main_block_female_room(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Female);
        $female = Employee::factory()->create(['gender' => Gender::Female]);

        $this->assertTrue(PlacementRules::canPlace($female, $room));
    }

    public function test_male_can_be_placed_in_main_block_male_room(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $male = Employee::factory()->create(['gender' => Gender::Male]);

        $this->assertTrue(PlacementRules::canPlace($male, $room));
    }

    public function test_female_can_be_placed_in_empty_male_labeled_main_room(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $female = Employee::factory()->create(['gender' => Gender::Female]);

        $this->assertTrue(PlacementRules::canPlace($female, $room));
    }

    public function test_female_cannot_enter_male_occupied_room(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $male = Employee::factory()->create(['gender' => Gender::Male]);
        $female = Employee::factory()->create(['gender' => Gender::Female]);

        app(\App\Services\PlacementService::class)->assign($male, $room);

        $this->assertFalse(PlacementRules::canPlace($female, $room->fresh(['activePlacements.employee'])));
    }

    public function test_female_can_join_female_occupant_in_male_labeled_room(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $first = Employee::factory()->create(['gender' => Gender::Female]);
        $second = Employee::factory()->create(['gender' => Gender::Female]);

        app(\App\Services\PlacementService::class)->assign($first, $room);

        $this->assertTrue(PlacementRules::canPlace($second, $room->fresh(['activePlacements.employee'])));
    }

    public function test_allowed_genders_for_empty_main_male_room_includes_female(): void
    {
        $room = $this->createRoom($this->mainFloor, Gender::Male);
        $genders = PlacementRules::allowedEmployeeGendersForRoom($room);

        $this->assertContains('female', $genders);
        $this->assertContains('male', $genders);
    }

    private function createRoom(Floor $floor, Gender $gender): Room
    {
        return Room::query()->create([
            'floor_id' => $floor->id,
            'room_number' => 'RULE-'.uniqid(),
            'capacity' => 4,
            'gender' => $gender,
            'status' => 'available',
        ]);
    }
}
