<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class CancelAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var Appointment|null $appointment */
        $appointment = $this->route('appointment');

        if ($user === null || $appointment === null) {
            return false;
        }

        if ($user->isAdminPortal()) {
            return true;
        }

        if ($user->isDoctor() && $user->doctor?->id === $appointment->doctor_id) {
            return true;
        }

        return $user->isPatient() && $user->patient?->id === $appointment->patient_id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cancellation_reason' => ['required', 'string', 'max:255'],
            'cancellation_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
