<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Enums\GenderPolicy;
use App\Enums\RoomStatus;
use App\Enums\TransferAction;
use App\Models\Employee;
use App\Models\Placement;
use App\Models\Room;
use App\Models\TransferHistory;
use App\Models\User;
use App\Support\PlacementRules;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlacementService
{
    public function __construct(
        private readonly CapacityService $capacityService,
        private readonly NotificationService $notificationService,
    ) {}

    public function assign(Employee $employee, Room $room, ?User $actor = null, ?string $notes = null): Placement
    {
        return DB::transaction(function () use ($employee, $room, $actor, $notes) {
            [$employee, $room] = $this->lockEmployeeAndRoom($employee->id, $room->id);

            $this->validateEmployee($employee);
            $this->validateRoom($room);
            $this->assertEmployeeAvailableForAssign($employee, $room);
            PlacementRules::assert($employee, $room);
            $this->assertRoomHasCapacity($room);

            $placement = Placement::query()->create([
                'employee_id' => $employee->id,
                'room_id' => $room->id,
                'assigned_at' => now(),
                'assigned_by' => $actor?->id,
                'notes' => $notes,
                'is_active' => true,
            ]);

            TransferHistory::query()->create([
                'employee_id' => $employee->id,
                'from_room_id' => null,
                'to_room_id' => $room->id,
                'action' => TransferAction::Assign,
                'performed_by' => $actor?->id,
                'created_at' => now(),
            ]);

            $this->capacityService->refreshRoomStatus($room);
            $this->notificationService->notifyPlacement($employee->full_name, $room->displayName());

            return $placement;
        });
    }

    public function transfer(Employee $employee, Room $room, ?User $actor = null, ?string $notes = null): Placement
    {
        return DB::transaction(function () use ($employee, $room, $actor, $notes) {
            [$employee, $room] = $this->lockEmployeeAndRoom($employee->id, $room->id);

            $this->validateEmployee($employee);
            $this->validateRoom($room);
            PlacementRules::assert($employee, $room);

            $existing = $this->resolveActivePlacement($employee);

            if (! $existing) {
                throw ValidationException::withMessages([
                    'employee_id' => 'Personelin aktif yerleşimi bulunmuyor.',
                ]);
            }

            if ((int) $existing->room_id === (int) $room->id) {
                throw ValidationException::withMessages([
                    'room_id' => 'Personel zaten bu odada.',
                ]);
            }

            $this->assertRoomHasCapacity($room);

            $fromRoomId = $existing->room_id;
            $existing->update(['is_active' => false]);

            $placement = Placement::query()->create([
                'employee_id' => $employee->id,
                'room_id' => $room->id,
                'assigned_at' => now(),
                'assigned_by' => $actor?->id,
                'notes' => $notes,
                'is_active' => true,
            ]);

            TransferHistory::query()->create([
                'employee_id' => $employee->id,
                'from_room_id' => $fromRoomId,
                'to_room_id' => $room->id,
                'action' => TransferAction::Transfer,
                'performed_by' => $actor?->id,
                'created_at' => now(),
            ]);

            if ($fromRoomId) {
                $this->capacityService->refreshRoomStatus(Room::query()->find($fromRoomId));
            }

            $this->capacityService->refreshRoomStatus($room);
            $this->notificationService->notifyPlacement($employee->full_name, $room->displayName());

            return $placement;
        });
    }

    public function remove(Employee $employee, ?User $actor = null, ?string $reason = null): void
    {
        DB::transaction(function () use ($employee, $actor, $reason) {
            $employee = Employee::query()->whereKey($employee->id)->lockForUpdate()->firstOrFail();
            $existing = $this->resolveActivePlacement($employee);

            if (! $existing) {
                throw ValidationException::withMessages([
                    'employee_id' => 'Personelin aktif yerleşimi bulunmuyor.',
                ]);
            }

            $room = Room::query()->whereKey($existing->room_id)->lockForUpdate()->first();
            $fromRoomId = $existing->room_id;

            $existing->update(['is_active' => false]);

            TransferHistory::query()->create([
                'employee_id' => $employee->id,
                'from_room_id' => $fromRoomId,
                'to_room_id' => null,
                'action' => TransferAction::Remove,
                'reason' => $reason,
                'performed_by' => $actor?->id,
                'created_at' => now(),
            ]);

            if ($room) {
                $this->capacityService->refreshRoomStatus($room);
            }
        });
    }

    /**
     * @param  list<int>  $employeeIds
     * @return array{success: int, failed: list<array{employee_id: int, message: string}>}
     */
    public function bulkAssignAuto(array $employeeIds, ?User $actor = null): array
    {
        $employeeIds = array_values(array_unique(array_map('intval', $employeeIds)));
        $failed = [];
        $success = 0;

        foreach ($employeeIds as $employeeId) {
            try {
                DB::transaction(function () use ($employeeId, $actor, &$success) {
                    $employee = Employee::query()->whereKey($employeeId)->lockForUpdate()->firstOrFail();
                    $room = $this->findAvailableRoomForEmployee($employee);

                    if (! $room) {
                        throw ValidationException::withMessages([
                            'room_id' => "{$employee->full_name} için uygun boş yer bulunamadı ({$employee->gender?->label()}).",
                        ]);
                    }

                    $room = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

                    $this->validateEmployee($employee);
                    $this->validateRoom($room);
                    $this->assertEmployeeAvailableForAssign($employee, $room);
                    PlacementRules::assert($employee, $room);
                    $this->assertRoomHasCapacity($room);

                    $placement = Placement::query()->create([
                        'employee_id' => $employee->id,
                        'room_id' => $room->id,
                        'assigned_at' => now(),
                        'assigned_by' => $actor?->id,
                        'is_active' => true,
                    ]);

                    TransferHistory::query()->create([
                        'employee_id' => $employee->id,
                        'from_room_id' => null,
                        'to_room_id' => $room->id,
                        'action' => TransferAction::BulkAssign,
                        'performed_by' => $actor?->id,
                        'created_at' => now(),
                    ]);

                    $this->capacityService->refreshRoomStatus($room);
                    $success++;
                });
            } catch (ValidationException $e) {
                $failed[] = [
                    'employee_id' => $employeeId,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Yerleştirilemedi.',
                ];
            }
        }

        return compact('success', 'failed');
    }

    /**
     * @param  list<int>  $employeeIds
     * @return array{success: int, failed: list<array{employee_id: int, message: string}>}
     */
    public function bulkAssign(array $employeeIds, Room $room, ?User $actor = null): array
    {
        $employeeIds = array_values(array_unique(array_map('intval', $employeeIds)));
        $failed = [];
        $success = 0;

        $room = $room->fresh();
        $freeSlots = $this->capacityService->freeBeds($room);

        if (count($employeeIds) > $freeSlots) {
            throw ValidationException::withMessages([
                'room_id' => 'Seçilen personel sayısı oda kapasitesini aşıyor. Boş yer: '.$freeSlots.'.',
            ]);
        }

        foreach ($employeeIds as $employeeId) {
            try {
                $this->assign($employee = Employee::query()->findOrFail($employeeId), $room->fresh(), $actor);
                $success++;
            } catch (ValidationException $e) {
                $failed[] = [
                    'employee_id' => $employeeId,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Yerleştirilemedi.',
                ];
            }
        }

        return compact('success', 'failed');
    }

    /**
     * @param  list<int>  $employeeIds
     * @return array{success: int, failed: list<array{employee_id: int, message: string}>}
     */
    public function bulkRemove(array $employeeIds, ?User $actor = null): array
    {
        $employeeIds = array_values(array_unique(array_map('intval', $employeeIds)));
        $failed = [];
        $success = 0;

        foreach ($employeeIds as $employeeId) {
            try {
                $employee = Employee::query()->findOrFail($employeeId);
                $this->remove($employee, $actor);
                $success++;
            } catch (ValidationException $e) {
                $failed[] = [
                    'employee_id' => $employeeId,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Çıkarılamadı.',
                ];
            }
        }

        return compact('success', 'failed');
    }

    private function findAvailableRoomForEmployee(Employee $employee): ?Room
    {
        if (! $employee->gender) {
            throw ValidationException::withMessages([
                'gender' => "{$employee->full_name} için cinsiyet bilgisi tanımlı değil.",
            ]);
        }

        return PlacementRules::availableRoomsQuery($employee)
            ->with(['floor.block'])
            ->orderBy('id')
            ->get()
            ->filter(fn (Room $room) => $this->capacityService->hasCapacity($room))
            ->sort(fn (Room $a, Room $b) => $this->roomPreferenceScore($b, $employee) <=> $this->roomPreferenceScore($a, $employee))
            ->first();
    }

    private function roomPreferenceScore(Room $room, Employee $employee): int
    {
        $room->loadMissing('floor.block');
        $score = $this->capacityService->currentOccupancy($room);

        if ($employee->gender === Gender::Female) {
            if ($room->floor?->block?->gender_policy === GenderPolicy::Female) {
                $score += 1000;
            }
            if ($room->gender === Gender::Female) {
                $score += 100;
            }
        } elseif ($room->gender === Gender::Male) {
            $score += 100;
        }

        return $score;
    }

    /**
     * @return array{0: Employee, 1: Room}
     */
    private function lockEmployeeAndRoom(int $employeeId, int $roomId): array
    {
        $employee = Employee::query()->whereKey($employeeId)->lockForUpdate()->firstOrFail();
        $room = Room::query()->whereKey($roomId)->lockForUpdate()->firstOrFail();

        return [$employee, $room];
    }

    private function resolveActivePlacement(Employee $employee): ?Placement
    {
        return Placement::query()
            ->where('employee_id', $employee->id)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();
    }

    private function assertEmployeeAvailableForAssign(Employee $employee, Room $room): void
    {
        $existing = $this->resolveActivePlacement($employee);

        if ($existing) {
            if ((int) $existing->room_id === (int) $room->id) {
                throw ValidationException::withMessages([
                    'employee_id' => "{$employee->full_name} zaten bu odada kayıtlı.",
                ]);
            }

            throw ValidationException::withMessages([
                'employee_id' => "{$employee->full_name} zaten bir odaya yerleşmiş. Önce çıkarın veya transfer kullanın.",
            ]);
        }

        if (Placement::query()
            ->where('room_id', $room->id)
            ->where('employee_id', $employee->id)
            ->where('is_active', true)
            ->exists()) {
            throw ValidationException::withMessages([
                'employee_id' => "{$employee->full_name} zaten bu odada kayıtlı.",
            ]);
        }
    }

    private function assertRoomHasCapacity(Room $room): void
    {
        if (! $this->capacityService->hasCapacity($room)) {
            throw ValidationException::withMessages([
                'room_id' => 'Oda kapasitesi dolu.',
            ]);
        }
    }

    private function validateEmployee(Employee $employee): void
    {
        if ($employee->status !== EmployeeStatus::Active) {
            throw ValidationException::withMessages([
                'employee_id' => 'Yalnızca aktif personel yerleştirilebilir.',
            ]);
        }

        if (! $employee->gender) {
            throw ValidationException::withMessages([
                'employee_id' => "{$employee->full_name} için cinsiyet bilgisi tanımlı değil.",
            ]);
        }
    }

    private function validateRoom(Room $room): void
    {
        if (in_array($room->status, [RoomStatus::Maintenance, RoomStatus::Inactive], true)) {
            throw ValidationException::withMessages([
                'room_id' => 'Bu oda yerleşime kapalı.',
            ]);
        }
    }
}
