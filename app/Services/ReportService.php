<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Enums\TransferAction;
use App\Models\Employee;
use App\Models\Placement;
use App\Models\Room;
use App\Models\TransferHistory;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportService
{
    public function summary(): array
    {
        $stats = app(DashboardService::class)->getStats();

        return [
            'generated_at' => now()->toDateTimeString(),
            'total_employees' => $stats['total_employees'],
            'assigned' => $stats['assigned_employees'],
            'unassigned' => $stats['unassigned_employees'],
            'total_rooms' => $stats['total_rooms'],
            'total_capacity' => $stats['total_capacity'],
            'total_occupied' => $stats['total_occupied'],
            'occupancy_rate' => $stats['occupancy_rate'],
        ];
    }

    public function occupancyReport(): Collection
    {
        return Room::query()
            ->with(['floor.block', 'activePlacements.employee'])
            ->withCount('activePlacements as occupancy')
            ->orderBy('id')
            ->get()
            ->map(fn (Room $room) => [
                'block' => $room->floor?->block?->name,
                'floor' => $room->floor?->name,
                'room_number' => $room->room_number,
                'gender' => $room->gender?->label(),
                'capacity' => $room->capacity,
                'occupied' => $room->occupancy,
                'available' => max(0, $room->capacity - $room->occupancy),
                'occupants' => $room->activePlacements->map(fn ($p) => [
                    'personnel_number' => $p->employee?->personnel_number,
                    'full_name' => $p->employee?->full_name,
                ])->values()->all(),
            ]);
    }

    public function unassignedReport(): Collection
    {
        return Employee::query()
            ->with('department')
            ->where('status', EmployeeStatus::Active)
            ->whereDoesntHave('activePlacement')
            ->orderBy('full_name')
            ->get()
            ->map(fn (Employee $e) => [
                'personnel_number' => $e->personnel_number,
                'full_name' => $e->full_name,
                'gender' => $e->gender?->label(),
                'department' => $e->department?->name,
            ]);
    }

    public function transferReport(): Collection
    {
        return TransferHistory::query()
            ->with(['employee', 'fromRoom.floor', 'toRoom.floor', 'performedBy'])
            ->latest('created_at')
            ->limit(500)
            ->get()
            ->map(fn (TransferHistory $t) => [
                'employee' => $t->employee?->full_name,
                'from' => $t->fromRoom ? $t->fromRoom->displayName() : '—',
                'to' => $t->toRoom ? $t->toRoom->displayName() : '—',
                'action' => $t->action?->label(),
                'date' => $t->created_at?->format('d.m.Y H:i'),
                'by' => $t->performedBy?->username ?? $t->performedBy?->name,
            ]);
    }

    /**
     * Grafik bileşenleri için hazır veri setleri.
     *
     * @return array{
     *     room_status: array{labels: list<string>, values: list<int>},
     *     capacity: array{labels: list<string>, values: list<int>},
     *     by_block: array{labels: list<string>, occupied: list<int>, capacity: list<int>, rates: list<float>},
     *     gender: array{labels: list<string>, assigned: list<int>, unassigned: list<int>},
     *     room_counts: array{full: int, warning: int, partial: int, empty: int}
     * }
     */
    public function chartDatasets(): array
    {
        $occupancy = $this->occupancyReport();
        $summary = $this->summary();

        $full = $occupancy->filter(fn ($r) => $r['available'] <= 0 && $r['occupied'] > 0)->count();
        $warning = $occupancy->filter(fn ($r) => $r['available'] === 1)->count();
        $empty = $occupancy->filter(fn ($r) => $r['occupied'] === 0)->count();
        $partial = $occupancy->count() - $full - $warning - $empty;

        $byBlock = $occupancy
            ->groupBy(fn ($r) => $r['block'] ?? 'Diğer')
            ->map(function (Collection $rooms, string $block) {
                $capacity = $rooms->sum('capacity');
                $occupied = $rooms->sum('occupied');

                return [
                    'block' => $block,
                    'capacity' => $capacity,
                    'occupied' => $occupied,
                    'rate' => $capacity > 0 ? round(($occupied / $capacity) * 100, 1) : 0.0,
                ];
            })
            ->sortByDesc('rate')
            ->values();

        $genderLabels = ['Erkek', 'Kadın'];
        $assignedByGender = Employee::query()
            ->where('status', EmployeeStatus::Active)
            ->whereHas('activePlacement')
            ->selectRaw('gender, COUNT(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $unassignedByGender = Employee::query()
            ->where('status', EmployeeStatus::Active)
            ->whereDoesntHave('activePlacement')
            ->selectRaw('gender, COUNT(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $genderAssigned = [
            (int) ($assignedByGender['male'] ?? 0),
            (int) ($assignedByGender['female'] ?? 0),
        ];
        $genderUnassigned = [
            (int) ($unassignedByGender['male'] ?? 0),
            (int) ($unassignedByGender['female'] ?? 0),
        ];

        $availableBeds = max(0, $summary['total_capacity'] - $summary['total_occupied']);

        return [
            'room_status' => [
                'labels' => ['Tam Dolu', 'Kritik (1 yatak)', 'Kısmen Dolu', 'Boş Oda'],
                'values' => [$full, $warning, max(0, $partial), $empty],
            ],
            'capacity' => [
                'labels' => ['Dolu Yatak', 'Boş Yatak'],
                'values' => [$summary['total_occupied'], $availableBeds],
            ],
            'by_block' => [
                'labels' => $byBlock->pluck('block')->all(),
                'occupied' => $byBlock->pluck('occupied')->map(fn ($v) => (int) $v)->all(),
                'capacity' => $byBlock->pluck('capacity')->map(fn ($v) => (int) $v)->all(),
                'rates' => $byBlock->pluck('rate')->map(fn ($v) => (float) $v)->all(),
            ],
            'gender' => [
                'labels' => $genderLabels,
                'assigned' => $genderAssigned,
                'unassigned' => $genderUnassigned,
            ],
            'room_counts' => [
                'full' => $full,
                'warning' => $warning,
                'partial' => max(0, $partial),
                'empty' => $empty,
            ],
        ];
    }

    public function executiveSummary(array $summary, array $roomCounts): array
    {
        $availableBeds = max(0, $summary['total_capacity'] - $summary['total_occupied']);
        $assignmentRate = $summary['total_employees'] > 0
            ? round(($summary['assigned'] / $summary['total_employees']) * 100, 1)
            : 0.0;

        $highlights = [];

        if ($summary['occupancy_rate'] >= 90) {
            $highlights[] = 'Lojman doluluk oranı %'.$summary['occupancy_rate'].' ile kritik seviyededir; kapasite planlaması önerilir.';
        } elseif ($summary['occupancy_rate'] >= 75) {
            $highlights[] = 'Doluluk oranı %'.$summary['occupancy_rate'].' ile yüksek seviyededir.';
        } else {
            $highlights[] = 'Genel doluluk oranı %'.$summary['occupancy_rate'].' ile yönetilebilir düzeydedir.';
        }

        if ($summary['unassigned'] > 0) {
            $highlights[] = number_format($summary['unassigned']).' aktif personel henüz odaya atanmamıştır.';
        }

        if ($roomCounts['warning'] > 0) {
            $highlights[] = $roomCounts['warning'].' odada yalnızca 1 boş yatak kalmıştır.';
        }

        if ($availableBeds > 0 && $summary['unassigned'] > $availableBeds) {
            $highlights[] = 'Atanmamış personel sayısı mevcut boş yatak sayısını aşmaktadır.';
        }

        return [
            'assignment_rate' => $assignmentRate,
            'available_beds' => $availableBeds,
            'highlights' => $highlights,
        ];
    }
}
