<?php

namespace Database\Factories;

use App\Models\ContractCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContractCompany>
 */
class ContractCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $percentage = fake()->numberBetween(15, 35);

        if (fake()->boolean()) {
            return [
                'name' => fake()->company(),
                'fee_type' => 'percentage',
                'percentage' => $percentage,
                'fixed_fee' => null,
                'phone' => fake()->phoneNumber(),
                'is_active' => true,
            ];
        }

        return [
            'name' => fake()->company(),
            'fee_type' => 'fixed',
            'fixed_fee' => fake()->randomFloat(2, 1, 10),
            'percentage' => null,
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }
}
