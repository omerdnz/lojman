<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Enums\RoomStatus;
use App\Models\Block;
use App\Models\Employee;
use App\Models\Floor;
use App\Models\Placement;
use App\Models\Room;

class DashboardService
{
    public function getStats(): array
    {
        $totalRooms = Room::query()->count();
        $totalEmployees = Employee::query()->where('status', EmployeeStatus::Active)->count();
        $totalCapacity = (int) Room::query()->sum('capacity');
        $totalOccupied = Placement::query()->where('is_active', true)->count();
        $assignedEmployees = Employee::query()
            ->where('status', EmployeeStatus::Active)
            ->whereHas('activePlacement')
            ->count();

        $occupiedRoomIds = Placement::query()
            ->where('is_active', true)
            ->distinct()
            ->pluck('room_id');

        $fullRooms = Room::query()
            ->withCount(['activePlacements as occupancy'])
            ->get()
            ->filter(fn (Room $room) => $room->occupancy >= $room->capacity)
            ->count();

        $emptyRooms = Room::query()
            ->whereDoesntHave('activePlacements')
            ->where('status', '!=', RoomStatus::Inactive)
            ->count();

        return [
            'total_blocks' => Block::query()->count(),
            'total_floors' => Floor::query()->count(),
            'total_rooms' => $totalRooms,
            'total_employees' => $totalEmployees,
            'assigned_employees' => $assignedEmployees,
            'unassigned_employees' => max(0, $totalEmployees - $assignedEmployees),
            'total_capacity' => $totalCapacity,
            'total_occupied' => $totalOccupied,
            'occupancy_rate' => $totalCapacity > 0
                ? round(($totalOccupied / $totalCapacity) * 100, 1)
                : 0,
            'full_rooms' => $fullRooms,
            'empty_rooms' => $emptyRooms,
            'rooms_with_availability' => $totalRooms - $fullRooms,
            'occupied_room_count' => $occupiedRoomIds->count(),
        ];
    }
}
