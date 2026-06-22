<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('permissions.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'permissions' => ['nullable', 'array'],
            'permissions.hr' => ['nullable', 'array'],
            'permissions.hr.*' => ['string'],
            'permissions.dorm_manager' => ['nullable', 'array'],
            'permissions.dorm_manager.*' => ['string'],
        ];
    }
}
