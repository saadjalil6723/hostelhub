<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by admin.auth middleware on the route
    }

    public function rules(): array
    {
        return [
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number'],
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
