<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'US Dollar',
                'Lebanese Pound'
            ]),
            'symbol' => fake()->randomElement(['$', 'LBP']),
            'rate' => fake()->randomFloat(4, 1, 90000),
            'is_default' => false,
        ];
    }
}
