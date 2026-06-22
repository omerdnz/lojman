<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('placements.bulk') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['integer', 'exists:employees,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_ids.required' => 'En az bir personel seçmelisiniz.',
            'employee_ids.min' => 'En az bir personel seçmelisiniz.',
        ];
    }
}
