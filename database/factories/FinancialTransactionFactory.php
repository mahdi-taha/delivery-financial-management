<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinancialTransaction>
 */
class FinancialTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currency = \App\Models\Currency::inRandomOrder()->first();

        $amount = fake()->randomFloat(2, 20, 500);

        return [

            'transaction_num' => fake()->unique()->numerify('TRX#####'),

            'date' => fake()->dateTimeBetween('-3 months'),

            'type' => fake()->randomElement([
                'payment',
                'receipt',
                'collection',
                'settlement',
                'refund',
                'adjustment',
                'other',
            ]),

            'direction' => fake()->randomElement([
                'in',
                'out',
            ]),

            'status' => 'completed',

            'amount' => $amount,
            'amount_base' => $amount * $currency->rate,

            'currency_id' => $currency->id,
            'exchange_rate' => $currency->rate,

            'driver_id' => fake()->boolean(60)
                ? \App\Models\Driver::inRandomOrder()->value('id')
                : null,

            'contract_company_id' => fake()->boolean(40)
                ? \App\Models\ContractCompany::inRandomOrder()->value('id')
                : null,

            'collection_id' => fake()->boolean(20)
                ? \App\Models\Collection::inRandomOrder()->value('id')
                : null,

            'settlement_id' => fake()->boolean(20)
                ? \App\Models\Settlement::inRandomOrder()->value('id')
                : null,

            'payment_method_id' => fake()->boolean()
                ? \App\Models\PaymentMethod::inRandomOrder()->value('id')
                : null,

            'notes' => fake()->optional()->sentence(),
        ];
    }
}
