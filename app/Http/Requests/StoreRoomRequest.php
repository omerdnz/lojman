<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\RoomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('rooms.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'floor_id' => ['required', 'exists:floors,id'],
            'room_number' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'status' => ['nullable', Rule::enum(RoomStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
