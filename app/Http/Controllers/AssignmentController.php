<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkAssignmentRequest;
use App\Http\Requests\BulkRemovalRequest;
use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Employee;
use App\Models\Room;
use App\Services\EmployeeService;
use App\Services\PlacementService;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function __construct(
        private readonly PlacementService $placementService,
        private readonly EmployeeService $employeeService,
        private readonly RoomService $roomService,
    ) {}

    public function index(): View
    {
        $unassignedEmployees = $this->employeeService->unassigned();
        $rooms = $this->roomService->withOccupancy();

        return view('assignments.index', compact('unassignedEmployees', 'rooms'));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $employee = Employee::query()->findOrFail($request->validated('employee_id'));
            $room = Room::query()->with('floor.block')->findOrFail($request->validated('room_id'));

            $this->placementService->assign(
                $employee,
                $room,
                $request->user(),
                $request->validated('notes'),
            );
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'İşlem başarısız.';

            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $message, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors());
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Yerleştirme tamamlandı.');
    }

    public function destroyByEmployee(Request $request): RedirectResponse|JsonResponse
    {
        abort_unless(
            $request->user()?->can('placements.remove') || $request->user()?->can('rooms.edit'),
            403
        );

        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        try {
            $employee = Employee::query()->findOrFail($validated['employee_id']);
            $this->placementService->remove($employee, $request->user());
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'İşlem başarısız.';

            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }

            return back()->withErrors($e->errors());
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Personel odadan çıkarıldı.');
    }

    public function bulkAssign(BulkAssignmentRequest $request): JsonResponse
    {
        $result = $this->placementService->bulkAssignAuto(
            $request->validated('employee_ids'),
            $request->user(),
        );

        return response()->json([
            'status' => 'success',
            'success' => $result['success'],
            'failed' => $result['failed'],
            'message' => $result['success'].' personel cinsiyetine uygun odalara yerleştirildi.'
                .(count($result['failed']) ? ' '.count($result['failed']).' kayıt başarısız.' : ''),
        ]);
    }

    public function bulkRemove(BulkRemovalRequest $request): JsonResponse
    {
        $result = $this->placementService->bulkRemove(
            $request->validated('employee_ids'),
            $request->user(),
        );

        return response()->json([
            'status' => 'success',
            'success' => $result['success'],
            'failed' => $result['failed'],
            'message' => $result['success'].' personel odadan çıkarıldı.'
                .(count($result['failed']) ? ' '.count($result['failed']).' kayıt başarısız.' : ''),
        ]);
    }

    public function transfer(Request $request): RedirectResponse|JsonResponse
    {
        abort_unless($request->user()?->can('placements.transfer'), 403);

        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
        ]);

        try {
            $employee = Employee::query()->findOrFail($validated['employee_id']);
            $room = Room::query()->with('floor.block')->findOrFail($validated['room_id']);
            $this->placementService->transfer($employee, $room, $request->user());
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'İşlem başarısız.';

            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }

            return back()->withErrors($e->errors());
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Transfer tamamlandı.');
    }
}
