<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        return $user->isAdminPortal() || $user->isDoctor();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => ['nullable', 'integer', 'exists:doctors,id'],
            'schedule_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'status' => ['required', Rule::in(['available', 'blocked'])],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
