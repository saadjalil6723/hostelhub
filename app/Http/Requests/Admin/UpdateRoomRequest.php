<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_number' => ['required', 'string', 'max:20', Rule::unique('rooms', 'room_number')->ignore($this->route('room'))],
            'floor' => ['nullable', 'string', 'max:20'],
            'room_type' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,partially_occupied,full,maintenance'],
            'facilities' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
