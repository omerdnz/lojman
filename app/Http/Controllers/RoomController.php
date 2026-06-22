<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Floor;
use App\Models\Room;
use App\Services\EmployeeService;
use App\Services\RoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
        private readonly EmployeeService $employeeService,
    ) {}

    public function index(): View
    {
        $rooms = $this->roomService->paginate();
        $unassignedEmployees = $this->employeeService->unassigned();

        return view('rooms.index', compact('rooms', 'unassignedEmployees'));
    }

    public function create(): View
    {
        $floors = Floor::query()->with('block')->where('is_active', true)->orderBy('sort_order')->get();

        return view('rooms.create', compact('floors'));
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $this->roomService->create($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Oda eklendi.');
    }

    public function edit(Room $room): View
    {
        $floors = Floor::query()->with('block')->where('is_active', true)->orderBy('sort_order')->get();

        return view('rooms.edit', compact('room', 'floors'));
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $this->roomService->update($room, $request->validated());

        return redirect()->route('rooms.index')->with('success', 'Oda güncellendi.');
    }

    public function destroy(Request $request, Room $room): RedirectResponse
    {
        abort_unless($request->user()?->can('rooms.delete'), 403);

        $this->roomService->delete($room);

        return redirect()->route('rooms.index')->with('success', 'Oda silindi.');
    }
}
