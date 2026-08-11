<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'driver_type' => fake()->randomElement(['percentage', 'salary']),
            'driver_percentage' => fake()->numberBetween(20, 50),
            'salary' => fake()->boolean()
                ? fake()->randomFloat(2, 300, 1200)
                : null,
            'notes' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
