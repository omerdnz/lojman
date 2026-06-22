<?php

namespace Database\Seeders;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Enums\RoomStatus;
use App\Enums\TransferAction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Placement;
use App\Models\Room;
use App\Models\TransferHistory;
use App\Services\CapacityService;
use App\Services\Legacy\LegacySqlParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyDataImportSeeder extends Seeder
{
    public function run(): void
    {
        $parser = LegacySqlParser::fromDefaultPath();
        $capacityService = app(CapacityService::class);

        $departmentMap = [];
        $employeeIds = [];
        $roomIds = [];
        $assignmentCount = 0;

        DB::transaction(function () use ($parser, $capacityService, &$departmentMap, &$employeeIds, &$roomIds, &$assignmentCount) {
            foreach ($parser->employees() as $row) {
                $deptName = trim($row['department'] ?? '') ?: 'GENEL';
                $code = BlockStructureSeeder::departmentCode($deptName);

                if (! isset($departmentMap[$deptName])) {
                    $department = Department::query()->firstOrCreate(
                        ['code' => $code],
                        [
                            'name' => $deptName,
                            'is_active' => true,
                        ]
                    );
                    $departmentMap[$deptName] = $department->id;
                }

                $gender = Gender::fromLegacy($row['gender']);

                Employee::query()->updateOrCreate(
                    ['legacy_id' => $row['id']],
                    [
                        'personnel_number' => sprintf('P%05d', $row['id']),
                        'full_name' => $row['name'],
                        'department_id' => $departmentMap[$deptName],
                        'gender' => $gender,
                        'status' => EmployeeStatus::Active,
                    ]
                );

                $employeeIds[$row['id']] = Employee::query()->where('legacy_id', $row['id'])->value('id');
            }

            foreach ($parser->rooms() as $row) {
                if ($row['floor_name'] === 'floor_name' || $row['room_no'] === 'room_no') {
                    continue;
                }

                $floorId = BlockStructureSeeder::floorIdForLegacyName($row['floor_name']);

                if (! $floorId) {
                    continue;
                }

                $gender = Gender::fromLegacy($row['gender'])
                    ?? BlockStructureSeeder::defaultGenderForFloor($row['floor_name']);

                $capacity = $capacityService->resolveCapacityForImport($row['capacity']);

                Room::query()->updateOrCreate(
                    ['legacy_id' => $row['id']],
                    [
                        'floor_id' => $floorId,
                        'room_number' => $row['room_no'],
                        'capacity' => $capacity,
                        'gender' => $gender,
                        'status' => RoomStatus::Available,
                    ]
                );

                $roomIds[$row['id']] = Room::query()->where('legacy_id', $row['id'])->value('id');
            }

            $seenEmployees = [];
            $assignmentCount = 0;

            foreach ($parser->assignments() as $row) {
                $employeeId = $employeeIds[$row['employee_id']] ?? null;
                $roomId = $roomIds[$row['room_id']] ?? null;

                if (! $employeeId || ! $roomId) {
                    continue;
                }

                if (isset($seenEmployees[$employeeId])) {
                    Placement::query()
                        ->where('employee_id', $employeeId)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }

                Placement::query()->create([
                    'employee_id' => $employeeId,
                    'room_id' => $roomId,
                    'assigned_at' => now(),
                    'assigned_by' => null,
                    'is_active' => true,
                ]);

                TransferHistory::query()->create([
                    'employee_id' => $employeeId,
                    'from_room_id' => null,
                    'to_room_id' => $roomId,
                    'action' => TransferAction::Assign,
                    'performed_by' => null,
                    'metadata' => ['source' => 'legacy_import'],
                    'created_at' => now(),
                ]);

                $seenEmployees[$employeeId] = true;
                $assignmentCount++;
            }

            Room::query()->each(fn (Room $room) => $capacityService->refreshRoomStatus($room));
        });

        $this->command?->info(sprintf(
            'Legacy import: %d personel, %d oda, %d atama.',
            count($employeeIds),
            count($roomIds),
            $assignmentCount
        ));
    }
}
