<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
