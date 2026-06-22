<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomService
{
    public function __construct(
        private readonly CapacityService $capacityService
    ) {}

    public function paginate(int $perPage = 30): LengthAwarePaginator
    {
        return Room::query()
            ->with(['floor.block', 'activePlacements.employee'])
            ->withCount('activePlacements as occupancy')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function withOccupancy(): Collection
    {
        return Room::query()
            ->with(['floor.block', 'activePlacements.employee'])
            ->withCount('activePlacements as occupancy')
            ->orderBy('id')
            ->get();
    }

    public function create(array $data): Room
    {
        $this->assertUniqueRoom($data['floor_id'], $data['room_number']);

        return Room::query()->create($data);
    }

    public function update(Room $room, array $data): Room
    {
        if (
            (isset($data['floor_id']) && $data['floor_id'] != $room->floor_id)
            || (isset($data['room_number']) && $data['room_number'] != $room->room_number)
        ) {
            $this->assertUniqueRoom($data['floor_id'] ?? $room->floor_id, $data['room_number'] ?? $room->room_number, $room->id);
        }

        if (isset($data['capacity']) && $data['capacity'] < $room->activePlacements()->count()) {
            throw ValidationException::withMessages(['capacity' => 'Kapasite mevcut doluluktan az olamaz.']);
        }

        $room->update($data);
        $this->capacityService->refreshRoomStatus($room->fresh());

        return $room->fresh(['floor.block']);
    }

    public function delete(Room $room): void
    {
        if ($room->activePlacements()->exists()) {
            throw ValidationException::withMessages(['room' => 'Dolu oda silinemez.']);
        }

        $room->delete();
    }

    private function assertUniqueRoom(int $floorId, string $roomNumber, ?int $exceptId = null): void
    {
        $exists = Room::query()
            ->where('floor_id', $floorId)
            ->where('room_number', $roomNumber)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['room_number' => 'Bu katta aynı oda numarası zaten var.']);
        }
    }
}
