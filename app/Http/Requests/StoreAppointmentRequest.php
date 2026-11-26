<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'string', 'max:20'],
            'visit_type' => ['nullable', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
