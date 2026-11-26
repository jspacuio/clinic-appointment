<?php

namespace App\Http\Requests\Doctor;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicalNotesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var Appointment|null $appointment */
        $appointment = $this->route('appointment');

        if ($user === null || $appointment === null) {
            return false;
        }

        return $user->isDoctor() && $user->doctor?->id === $appointment->doctor_id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'clinical_notes' => ['required', 'string', 'max:5000'],
            'is_draft' => ['sometimes', 'boolean'],
        ];
    }
}
