<?php

namespace App\Http\Requests\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
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
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'doctor_id' => ['required', 'integer', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date'],
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
                if ($validator->errors()->hasAny(['doctor_id', 'appointment_date', 'start_time'])) {
                    return;
                }

                $doctor = Doctor::find($this->integer('doctor_id'));

                if ($doctor === null || ! $doctor->is_active) {
                    $validator->errors()->add('doctor_id', 'Selected doctor is not active.');

                    return;
                }

                $date = $this->string('appointment_date')->toString();
                $time = $this->string('start_time')->toString();

                $isBlocked = DoctorSchedule::where('doctor_id', $doctor->id)
                    ->whereDate('schedule_date', $date)
                    ->where('start_time', $time)
                    ->where('status', 'blocked')
                    ->exists();

                if ($isBlocked) {
                    $validator->errors()->add('start_time', 'This time slot is blocked in the doctor schedule.');

                    return;
                }

                $hasConflict = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $date)
                    ->where('start_time', $time)
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->exists();

                if ($hasConflict) {
                    $validator->errors()->add('start_time', 'This time was just booked. Please choose another available slot.');
                }
            },
        ];
    }
}
