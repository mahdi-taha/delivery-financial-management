<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'action' =>
            fake()->randomElement([
                'create',
                'update',
                'delete'
            ]),

            'table_name' =>
            fake()->word(),

            'date' => now(),

            'record_id' =>
            fake()->numberBetween(1, 100),

            'old_values' => null,

            'new_values' => null,

            'ip_address' => fake()->ipv4()

        ];
    }
}
