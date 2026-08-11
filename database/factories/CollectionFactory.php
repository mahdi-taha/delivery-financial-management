<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currency = \App\Models\Currency::inRandomOrder()->first();

        $received = fake()->randomFloat(2, 100, 1000);

        $driver = $received * 0.35;

        $company = $received - $driver;

        return [

            'date' => fake()->dateTimeBetween('-2 months'),

            'collection_num' => fake()->unique()->numerify('COL#####'),

            'payment_method_id' => \App\Models\PaymentMethod::inRandomOrder()->value('id'),

            'driver_id' => \App\Models\Driver::inRandomOrder()->value('id'),

            'received_amount' => $received,
            'driver_amount' => $driver,
            'company_amount' => $company,

            'received_amount_base' => $received * $currency->rate,
            'driver_amount_base' => $driver * $currency->rate,
            'company_amount_base' => $company * $currency->rate,

            'currency_id' => $currency->id,
            'exchange_rate' => $currency->rate,

            'status' => fake()->randomElement([
                'pending',
                'received',
            ]),

            'notes' => fake()->optional()->sentence(),
        ];
    }
}
