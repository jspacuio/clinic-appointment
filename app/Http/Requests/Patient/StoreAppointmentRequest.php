<?php

namespace App\Http\Requests\Patient;

use App\Models\Doctor;
use App\Services\WeeklyScheduleBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPatient() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'specialization' => ['required', 'string', 'max:120'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'visit_type' => ['required', 'string', 'max:100'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['specialization', 'appointment_date', 'start_time'])) {
                    return;
                }

                $specialization = $this->string('specialization')->toString();

                $hasDoctorsForConcern = Doctor::where('is_active', true)
                    ->where('specialization', $specialization)
                    ->exists();

                if (! $hasDoctorsForConcern) {
                    $validator->errors()->add('specialization', 'No active doctors are currently available for this medical concern.');

                    return;
                }

                $date = $this->string('appointment_date')->toString();
                $time = $this->string('start_time')->toString();

                $availableDoctors = app(WeeklyScheduleBuilder::class)
                    ->findAvailableDoctorsForSlot($specialization, $date, $time);

                if ($availableDoctors->isEmpty()) {
                    $validator->errors()->add('start_time', 'No doctors for the selected concern are available at this time slot. Please choose another slot.');
                }
            },
        ];
    }
}
