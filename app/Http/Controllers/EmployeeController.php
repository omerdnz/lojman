<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    ) {}

    public function index(Request $request): View
    {
        $employees = $this->employeeService->paginate(
            $request->query('search'),
            $request->query('gender'),
            $request->query('department_id') ? (int) $request->query('department_id') : null,
        );
        $departments = $this->employeeService->departmentsList();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('employees.create'), 403);
        $departments = $this->employeeService->departmentsList();
        $nextPersonnelNumber = $this->employeeService->nextPersonnelNumber();

        return view('employees.create', compact('departments', 'nextPersonnelNumber'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->create($request->validated(), $request->user());

        return redirect()->route('employees.index')->with('success', 'Personel eklendi.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'activePlacement.room.floor.block', 'transferHistories.performedBy', 'histories']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        abort_unless(auth()->user()?->can('employees.edit'), 403);
        $departments = $this->employeeService->departmentsList();

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->employeeService->update($employee, $request->validated(), $request->user());

        return redirect()->route('employees.index')->with('success', 'Personel güncellendi.');
    }

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        abort_unless($request->user()?->can('employees.delete'), 403);

        $this->employeeService->delete($employee, $request->user());

        return redirect()->route('employees.index')->with('success', 'Personel silindi.');
    }
}
