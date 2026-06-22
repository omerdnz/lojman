<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Room;

class CapacityService
{
    public function __construct(
        private readonly SettingsService $settings
    ) {}

    public function currentOccupancy(Room $room): int
    {
        return $room->activePlacements()->count();
    }

    public function effectiveCapacity(Room $room): int
    {
        return max(1, (int) $room->capacity);
    }

    public function hasCapacity(Room $room, int $additional = 1): bool
    {
        return ($this->currentOccupancy($room) + $additional) <= $this->effectiveCapacity($room);
    }

    public function freeBeds(Room $room): int
    {
        return max(0, $this->effectiveCapacity($room) - $this->currentOccupancy($room));
    }

    public function resolveCapacityForImport(int $legacyCapacity): int
    {
        if ($legacyCapacity > 0) {
            return $legacyCapacity;
        }

        return $this->settings->defaultRoomCapacity();
    }

    public function refreshRoomStatus(Room $room): void
    {
        if ($room->status === RoomStatus::Maintenance || $room->status === RoomStatus::Inactive) {
            return;
        }

        $occupancy = $this->currentOccupancy($room);
        $capacity = $this->effectiveCapacity($room);

        $status = match (true) {
            $occupancy === 0 => RoomStatus::Available,
            $occupancy >= $capacity => RoomStatus::Full,
            default => RoomStatus::Partial,
        };

        $room->update(['status' => $status]);
    }
}
