<?php

namespace Database\Factories;

use App\Models\CompanyInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyInfo>
 */
class CompanyInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => fake()->company(),

            'logo' => null,

            'phone' => fake()->phoneNumber()

        ];
    }
}
