<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->doctor(),
            'full_name' => 'Dr. '.fake()->name(),
            'specialization' => fake()->randomElement(['General Medicine', 'Pediatrics', 'Dermatology', 'Cardiology']),
            'license_number' => 'MD-'.fake()->unique()->numerify('######'),
            'phone' => '(555) '.fake()->numerify('###-####'),
            'email' => fake()->unique()->safeEmail(),
            'consultation_duration' => 30,
            'availability_summary' => 'Mon–Fri · 8:00 AM–4:00 PM',
            'avatar_tone' => fake()->randomElement(['teal', 'blue', 'purple', 'amber']),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
