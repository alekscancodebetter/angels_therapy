<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Customer;
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
            'customer_id' => Customer::factory(),
            'treatment_slug' => fake()->randomElement(array_keys(config('treatments'))),
            'starts_at' => fake()->dateTimeBetween('now', '+2 months'),
            'duration_minutes' => fake()->randomElement([45, 60, 75, 90]),
            'status' => 'scheduled',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
