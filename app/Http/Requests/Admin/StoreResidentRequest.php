<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Only normalize into the dashed CNIC shape when the resident is
     * actually recording a CNIC — passport/other IDs are left as typed,
     * so foreign residents aren't forced through a Pakistani ID format.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('identification_number') && $this->input('id_type', 'cnic') === 'cnic') {
            $digits = preg_replace('/\D/', '', $this->identification_number);

            $normalized = strlen($digits) === 13
                ? substr($digits, 0, 5).'-'.substr($digits, 5, 7).'-'.substr($digits, 12, 1)
                : trim($this->identification_number);

            $this->merge(['identification_number' => $normalized]);
        } elseif ($this->filled('identification_number')) {
            $this->merge(['identification_number' => trim($this->identification_number)]);
        }

        if ($this->filled('phone')) {
            $this->merge(['phone' => trim($this->phone)]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'id_type' => ['required', 'in:cnic,passport,other'],
            'identification_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::when($this->input('id_type') === 'cnic', ['regex:/^\d{5}-\d{7}-\d{1}$/']),
                'unique:residents,identification_number',
            ],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\+92|0)[0-9]{10}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:2000'],
            'emergency_contact' => ['nullable', 'string', 'max:50'],
            'check_in_date' => ['nullable', 'date'],
            'check_out_date' => ['nullable', 'date', 'after_or_equal:check_in_date'],
            'status' => ['required', 'in:active,checked_out,inactive'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'identification_number.regex' => 'CNIC must be in the format 12345-1234567-1 (13 digits).',
            'identification_number.unique' => 'This ID number is already registered to another resident.',
            'phone.regex' => 'Enter a valid phone number, e.g. 03001234567 or +923001234567.',
        ];
    }
}
