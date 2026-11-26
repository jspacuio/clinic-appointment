<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'sex' => ['nullable', 'string', 'in:Female,Male,Prefer not to say'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'string', 'max:255'],
        ];
    }
}
