<?php

namespace App\Http\Requests;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('employees.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'personnel_number' => ['required', 'string', 'max:50', 'unique:employees,personnel_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'department_id' => ['nullable', 'exists:departments,id'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'hire_date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::enum(EmployeeStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
