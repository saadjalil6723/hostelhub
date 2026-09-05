<?php

namespace App\Http\Requests\Admin;

use App\Models\RoomAllocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRoomAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resident_id' => ['required', 'exists:residents,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'bed_number' => ['nullable', 'string', 'max:20'],
            'allocation_date' => ['required', 'date'],
            // Set by the form when the admin has seen the "resident already
            // has an active allocation" warning and explicitly chosen to proceed.
            'confirm_reallocate' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Block a resident being placed into two rooms at once unless the admin
     * has explicitly acknowledged it (confirm_reallocate=1) from the form's
     * warning banner. This mirrors the CNIC duplicate guard: the client-side
     * check is UX, this is the real gate.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->filled('resident_id') || $this->boolean('confirm_reallocate')) {
                return;
            }

            $existing = RoomAllocation::with('room')
                ->where('resident_id', $this->resident_id)
                ->where('status', 'active')
                ->first();

            if ($existing) {
                $validator->errors()->add(
                    'resident_id',
                    'This resident already has an active allocation in Room '.
                        ($existing->room->room_number ?? '#'.$existing->room_id).
                        '. End that allocation first, or check "Reallocate anyway" to proceed.'
                );
            }
        });
    }
}
