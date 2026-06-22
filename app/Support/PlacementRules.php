<?php

namespace App\Support;

use App\Enums\Gender;
use App\Enums\GenderPolicy;
use App\Enums\RoomStatus;
use App\Models\Employee;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PlacementRules
{
    /**
     * Kızlar bloğu: yalnızca kadın personel.
     * Ana/karma blok: boş odalara kız veya erkek; dolu odada yalnızca aynı cinsiyet.
     */
    public static function canPlace(Employee $employee, Room $room): bool
    {
        return self::validate($employee, $room) === null;
    }

    public static function validate(Employee $employee, Room $room): ?string
    {
        $room->loadMissing(['floor.block', 'activePlacements.employee']);

        if (! $employee->gender) {
            return 'Personel cinsiyet bilgisi tanımlı değil.';
        }

        $policy = $room->floor?->block?->gender_policy;

        if ($policy === GenderPolicy::Female && $employee->gender === Gender::Male) {
            return 'Kızlar bloğuna yalnızca kadın personel yerleştirilebilir.';
        }

        $occupantGenders = self::occupantGenders($room);

        if ($occupantGenders->contains(fn (Gender $gender) => $gender !== $employee->gender)) {
            return 'Kız ve erkek aynı odada kalamaz.';
        }

        if ($occupantGenders->isNotEmpty()) {
            return null;
        }

        if (self::isMainLodgingPolicy($policy)) {
            return null;
        }

        if ($policy === GenderPolicy::Female && $employee->gender === Gender::Female) {
            return null;
        }

        if ($room->gender && $employee->gender !== $room->gender) {
            return 'Kız ve erkek aynı odada kalamaz.';
        }

        return null;
    }

    public static function assert(Employee $employee, Room $room): void
    {
        $message = self::validate($employee, $room);

        if ($message !== null) {
            throw ValidationException::withMessages(['placement' => $message]);
        }
    }

    /**
     * @return Builder<Room>
     */
    public static function availableRoomsQuery(Employee $employee): Builder
    {
        if (! $employee->gender) {
            return Room::query()->whereRaw('1 = 0');
        }

        return Room::query()
            ->whereNotIn('status', [RoomStatus::Maintenance, RoomStatus::Inactive])
            ->whereHas('floor.block', function (Builder $query) use ($employee) {
                if ($employee->gender === Gender::Male) {
                    $query->where('gender_policy', '!=', GenderPolicy::Female->value);
                } else {
                    $query->whereIn('gender_policy', [
                        GenderPolicy::Female->value,
                        GenderPolicy::Mixed->value,
                        GenderPolicy::Male->value,
                    ]);
                }
            })
            ->whereDoesntHave('activePlacements', function (Builder $query) use ($employee) {
                $query->whereHas('employee', fn (Builder $q) => $q->where('gender', '!=', $employee->gender->value));
            });
    }

    public static function roomAllowedForEmployee(Room $room, Gender $employeeGender): bool
    {
        $room->loadMissing(['floor.block', 'activePlacements.employee']);

        $policy = $room->floor?->block?->gender_policy;

        if ($employeeGender === Gender::Male && $policy === GenderPolicy::Female) {
            return false;
        }

        $occupantGenders = self::occupantGenders($room);

        if ($occupantGenders->contains(fn (Gender $gender) => $gender !== $employeeGender)) {
            return false;
        }

        if ($occupantGenders->isNotEmpty()) {
            return true;
        }

        if (self::isMainLodgingPolicy($policy)) {
            return true;
        }

        if ($policy === GenderPolicy::Female && $employeeGender === Gender::Female) {
            return true;
        }

        if ($room->gender && $room->gender !== $employeeGender) {
            return false;
        }

        return true;
    }

    /**
     * @return list<string>
     */
    public static function allowedEmployeeGendersForRoom(Room $room): array
    {
        $room->loadMissing(['floor.block', 'activePlacements.employee']);

        $policy = $room->floor?->block?->gender_policy;
        $occupants = self::occupantGenders($room);

        if ($occupants->isNotEmpty()) {
            return [$occupants->first()->value];
        }

        if ($policy === GenderPolicy::Female) {
            return [Gender::Female->value];
        }

        return [Gender::Male->value, Gender::Female->value];
    }

    private static function isMainLodgingPolicy(?GenderPolicy $policy): bool
    {
        return in_array($policy, [GenderPolicy::Mixed, GenderPolicy::Male], true);
    }

    /**
     * @return Collection<int, Gender>
     */
    private static function occupantGenders(Room $room): Collection
    {
        return $room->activePlacements
            ->map(fn ($placement) => $placement->employee?->gender)
            ->filter()
            ->unique()
            ->values();
    }
}
