<?php

namespace Tests\Feature;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Enums\RoomStatus;
use App\Models\Block;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Floor;
use App\Models\Placement;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomPlacementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Room $maleRoom;

    private Employee $maleEmployee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::query()->where('username', 'admin')->first();

        $block = Block::query()->first();
        $floor = Floor::query()->where('block_id', $block->id)->first();

        $this->maleRoom = Room::query()->create([
            'floor_id' => $floor->id,
            'room_number' => 'TEST-99',
            'capacity' => 4,
            'gender' => Gender::Male,
            'status' => RoomStatus::Available,
        ]);

        $dept = Department::query()->first();

        $this->maleEmployee = Employee::query()->create([
            'personnel_number' => 'TEST001',
            'full_name' => 'Test Personel',
            'gender' => Gender::Male,
            'department_id' => $dept->id,
            'status' => EmployeeStatus::Active,
        ]);
    }

    public function test_assignments_page_includes_placement_scripts(): void
    {
        $response = $this->actingAs($this->admin)->get(route('assignments.index'));

        $response->assertOk();
        $response->assertSee('LojmanPlacement', false);
        $response->assertSee('openRoomModal', false);
        $response->assertSee('open-room-modal-btn', false);
        $response->assertDontSee('onclick="openRoomModal', false);
        $response->assertSee('data-allowed-genders', false);
        $this->assertMatchesRegularExpression(
            "/data-allowed-genders='(\[[^\]]*\])'/",
            $response->getContent(),
        );
    }

    public function test_rooms_page_includes_placement_scripts(): void
    {
        $response = $this->actingAs($this->admin)->get(route('rooms.index'));

        $response->assertOk();
        $response->assertSee('LojmanPlacement', false);
        $response->assertSee('openAddModal', false);
        $response->assertSee('open-add-modal-btn', false);
        $response->assertSee('data-allowed-genders', false);
        $this->assertMatchesRegularExpression(
            "/data-allowed-genders='(\[[^\]]*\])'/",
            $response->getContent(),
        );
        $response->assertDontSee('onclick="openAddModal', false);
    }

    public function test_rooms_page_shows_single_placement_actions(): void
    {
        $response = $this->actingAs($this->admin)->get(route('rooms.index'));

        $response->assertOk();
        $response->assertSee('Personel Ekle');
        $response->assertDontSee('Toplu Yerleştir');
    }

    public function test_assignments_page_shows_placement_and_bulk_actions(): void
    {
        $response = $this->actingAs($this->admin)->get(route('assignments.index'));

        $response->assertOk();
        $response->assertSee('Odaya Ata');
        $response->assertSee('Toplu Yerleştir');
        $response->assertSee('Toplu Çıkar');
    }

    public function test_can_assign_employee_to_room_via_api(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('assignments.store'), [
            'employee_id' => $this->maleEmployee->id,
            'room_id' => $this->maleRoom->id,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('placements', [
            'employee_id' => $this->maleEmployee->id,
            'room_id' => $this->maleRoom->id,
            'is_active' => true,
        ]);
    }

    public function test_can_remove_employee_from_room(): void
    {
        $this->actingAs($this->admin)->postJson(route('assignments.store'), [
            'employee_id' => $this->maleEmployee->id,
            'room_id' => $this->maleRoom->id,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.remove-by-employee'), [
            'employee_id' => $this->maleEmployee->id,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseMissing('placements', [
            'employee_id' => $this->maleEmployee->id,
            'is_active' => true,
        ]);
    }

    public function test_can_bulk_assign_employees_auto_by_gender(): void
    {
        $employee2 = Employee::query()->create([
            'personnel_number' => 'TEST002',
            'full_name' => 'Test Personel 2',
            'gender' => Gender::Male,
            'department_id' => $this->maleEmployee->department_id,
            'status' => EmployeeStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.bulk-assign'), [
            'employee_ids' => [$this->maleEmployee->id, $employee2->id],
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success', 'success' => 2]);

        foreach ([$this->maleEmployee->id, $employee2->id] as $employeeId) {
            $placement = Placement::query()
                ->where('employee_id', $employeeId)
                ->where('is_active', true)
                ->with('room')
                ->first();

            $this->assertNotNull($placement);
            $this->assertSame(Gender::Male, $placement->room->gender);
        }
    }

    public function test_bulk_assign_routes_male_and_female_to_matching_rooms(): void
    {
        $block = Block::query()->first();
        $floor = Floor::query()->where('block_id', $block->id)->first();

        $femaleRoom = Room::query()->create([
            'floor_id' => $floor->id,
            'room_number' => 'TEST-F-99',
            'capacity' => 4,
            'gender' => Gender::Female,
            'status' => RoomStatus::Available,
        ]);

        $femaleEmployee = Employee::query()->create([
            'personnel_number' => 'TESTF01',
            'full_name' => 'Test Kadın Personel',
            'gender' => Gender::Female,
            'department_id' => $this->maleEmployee->department_id,
            'status' => EmployeeStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.bulk-assign'), [
            'employee_ids' => [$this->maleEmployee->id, $femaleEmployee->id],
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success', 'success' => 2]);

        $malePlacement = Placement::query()
            ->where('employee_id', $this->maleEmployee->id)
            ->where('is_active', true)
            ->with('room')
            ->first();
        $femalePlacement = Placement::query()
            ->where('employee_id', $femaleEmployee->id)
            ->where('is_active', true)
            ->with('room')
            ->first();

        $this->assertNotNull($malePlacement);
        $this->assertNotNull($femalePlacement);
        $this->assertSame(Gender::Male, $malePlacement->room->gender);
        $this->assertSame(Gender::Female, $femalePlacement->room->gender);
    }

    public function test_bulk_assign_places_females_in_empty_main_rooms(): void
    {
        $mainBlock = Block::query()->where('code', 'ANA_LOJMAN')->first();
        $mainFloor = Floor::query()->where('block_id', $mainBlock->id)->first();

        $emptyMaleRoom = Room::query()->create([
            'floor_id' => $mainFloor->id,
            'room_number' => 'TEST-EMPTY-M',
            'capacity' => 4,
            'gender' => Gender::Male,
            'status' => RoomStatus::Available,
        ]);

        $femaleEmployee = Employee::query()->create([
            'personnel_number' => 'TESTF02',
            'full_name' => 'Test Kadın 2',
            'gender' => Gender::Female,
            'department_id' => $this->maleEmployee->department_id,
            'status' => EmployeeStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.bulk-assign'), [
            'employee_ids' => [$femaleEmployee->id],
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success', 'success' => 1]);

        $placement = Placement::query()
            ->where('employee_id', $femaleEmployee->id)
            ->where('is_active', true)
            ->with('room.floor.block')
            ->first();

        $this->assertNotNull($placement);
        $this->assertSame(Gender::Female, $femaleEmployee->gender);
    }

    public function test_can_assign_female_to_empty_main_male_room(): void
    {
        $mainBlock = Block::query()->whereIn('code', ['ANA_LOJMAN', 'ERKEK_LOJMAN'])->first();
        $mainFloor = Floor::query()->where('block_id', $mainBlock->id)->first();

        $emptyMaleRoom = Room::query()->create([
            'floor_id' => $mainFloor->id,
            'room_number' => 'TEST-ROOMS-F',
            'capacity' => 4,
            'gender' => Gender::Male,
            'status' => RoomStatus::Available,
        ]);

        $femaleEmployee = Employee::query()->create([
            'personnel_number' => 'TESTF03',
            'full_name' => 'Test Kadın Odalar',
            'gender' => Gender::Female,
            'department_id' => $this->maleEmployee->department_id,
            'status' => EmployeeStatus::Active,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.store'), [
            'employee_id' => $femaleEmployee->id,
            'room_id' => $emptyMaleRoom->id,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('placements', [
            'employee_id' => $femaleEmployee->id,
            'room_id' => $emptyMaleRoom->id,
            'is_active' => true,
        ]);
    }

    public function test_male_cannot_be_placed_in_girls_block_via_api(): void
    {
        $girlsBlock = Block::query()->where('code', 'KIZLAR_LOJMANI')->first();
        $girlsFloor = Floor::query()->where('block_id', $girlsBlock->id)->first();

        $girlsRoom = Room::query()->create([
            'floor_id' => $girlsFloor->id,
            'room_number' => 'TEST-GIRLS',
            'capacity' => 4,
            'gender' => Gender::Female,
            'status' => RoomStatus::Available,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.store'), [
            'employee_id' => $this->maleEmployee->id,
            'room_id' => $girlsRoom->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_can_bulk_remove_employees_from_room(): void
    {
        $this->actingAs($this->admin)->postJson(route('assignments.store'), [
            'employee_id' => $this->maleEmployee->id,
            'room_id' => $this->maleRoom->id,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('assignments.bulk-remove'), [
            'employee_ids' => [$this->maleEmployee->id],
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success', 'success' => 1]);

        $this->assertDatabaseMissing('placements', [
            'employee_id' => $this->maleEmployee->id,
            'is_active' => true,
        ]);
    }
}
