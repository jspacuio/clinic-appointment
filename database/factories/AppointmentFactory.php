<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_code' => 'APT-'.fake()->unique()->numberBetween(2000, 9999),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'booked_by_user_id' => User::factory()->admin(),
            'appointment_date' => now()->toDateString(),
            'start_time' => fake()->randomElement(['09:00:00', '10:00:00', '11:00:00', '13:30:00', '15:00:00']),
            'duration_minutes' => 30,
            'visit_type' => fake()->randomElement(['General Checkup', 'Follow-up', 'Consultation', 'Dermatology']),
            'reason' => 'Routine consultation visit',
            'status' => AppointmentStatus::Confirmed,
            'confirmed_at' => now(),
        ];
    }
}
