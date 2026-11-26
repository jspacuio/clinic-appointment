<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_code' => 'PC-'.fake()->unique()->numberBetween(1000, 9999),
            'full_name' => fake()->name(),
            'date_of_birth' => fake()->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            'sex' => fake()->randomElement(['Female', 'Male']),
            'phone' => '(555) '.fake()->numerify('###-####'),
            'email' => fake()->unique()->safeEmail(),
            'emergency_contact' => fake()->name().' · (555) '.fake()->numerify('###-####'),
            'address' => fake()->streetAddress().', '.fake()->city(),
            'avatar_tone' => fake()->randomElement(['purple', 'blue', 'amber', 'teal', 'pink']),
            'is_active' => true,
        ];
    }
}
