<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:Pending,Confirmed,In Progress,Completed,Cancelled,No-show'],
            'cancellation_reason' => ['nullable', 'string', 'max:255'],
            'cancellation_notes' => ['nullable', 'string', 'max:1000'],
            'consultation_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
