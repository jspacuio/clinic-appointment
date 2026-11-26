<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminPortal() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:120'],
            'license_number' => ['required', 'string', 'max:50', Rule::unique('doctors', 'license_number')],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('doctors', 'email'), Rule::unique('users', 'email')],
            'consultation_duration' => ['required', 'integer', 'min:10', 'max:120'],
            'availability_summary' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
