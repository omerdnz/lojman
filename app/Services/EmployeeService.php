<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeHistory;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmployeeService
{
    public function paginate(?string $search = null, ?string $gender = null, ?int $departmentId = null, int $perPage = 50): LengthAwarePaginator
    {
        return Employee::query()
            ->with(['department', 'activePlacement.room.floor.block'])
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('personnel_number', 'like', "%{$search}%");
            }))
            ->when($gender && $gender !== 'all', fn ($q) => $q->where('gender', $gender))
            ->when($departmentId && $departmentId !== 0, fn ($q) => $q->where('department_id', $departmentId))
            ->orderBy('full_name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function unassigned(?Gender $gender = null): Collection
    {
        return Employee::query()
            ->with('department')
            ->where('status', EmployeeStatus::Active)
            ->whereDoesntHave('activePlacement')
            ->when($gender, fn ($q) => $q->where('gender', $gender))
            ->orderBy('full_name')
            ->get();
    }

    public function create(array $data, ?User $actor = null): Employee
    {
        return DB::transaction(function () use ($data, $actor) {
            if (Employee::query()->where('personnel_number', $data['personnel_number'])->exists()) {
                throw ValidationException::withMessages(['personnel_number' => 'Bu sicil numarası zaten kayıtlı.']);
            }

            $employee = Employee::query()->create([
                ...$data,
                'created_by' => $actor?->id,
                'updated_by' => $actor?->id,
            ]);

            $this->logHistory($employee, 'created', null, $actor);

            return $employee;
        });
    }

    public function update(Employee $employee, array $data, ?User $actor = null): Employee
    {
        return DB::transaction(function () use ($employee, $data, $actor) {
            if (
                isset($data['personnel_number'])
                && $data['personnel_number'] !== $employee->personnel_number
                && Employee::query()->where('personnel_number', $data['personnel_number'])->where('id', '!=', $employee->id)->exists()
            ) {
                throw ValidationException::withMessages(['personnel_number' => 'Bu sicil numarası zaten kayıtlı.']);
            }

            $old = $employee->only(array_keys($data));
            $employee->update([...$data, 'updated_by' => $actor?->id]);
            $this->logHistory($employee, 'updated', $old, $actor);

            return $employee->fresh();
        });
    }

    public function deactivate(Employee $employee, ?User $actor = null): Employee
    {
        return $this->update($employee, ['status' => EmployeeStatus::Inactive], $actor);
    }

    public function delete(Employee $employee, ?User $actor = null): void
    {
        DB::transaction(function () use ($employee, $actor) {
            $employee->activePlacement?->update(['is_active' => false]);
            $this->logHistory($employee, 'deleted', null, $actor);
            $employee->delete();
        });
    }

    public function nextPersonnelNumber(): string
    {
        $max = Employee::query()
            ->pluck('personnel_number')
            ->map(fn (string $number) => (int) preg_replace('/\D/', '', $number))
            ->max() ?? 0;

        return sprintf('P%05d', $max + 1);
    }

    public function departmentsList(): Collection
    {
        return Department::query()->where('is_active', true)->orderBy('name')->get();
    }

    private function logHistory(Employee $employee, string $action, ?array $old, ?User $actor): void
    {
        EmployeeHistory::query()->create([
            'employee_id' => $employee->id,
            'action' => $action,
            'changed_fields' => $old ? ['old' => $old, 'new' => $employee->only(array_keys($old))] : null,
            'performed_by' => $actor?->id,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 500),
            'created_at' => now(),
        ]);
    }
}
