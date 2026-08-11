<?php

namespace Database\Factories;

use App\Models\ContractCompany;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currency = \App\Models\Currency::inRandomOrder()->first();

        $fee = fake()->randomFloat(2, 3, 20);

        $rate = $currency->rate;

        $driver = \App\Models\Driver::inRandomOrder()->first();

        $company = \App\Models\ContractCompany::inRandomOrder()->first();

        if ($company->fee_type == 'percentage') {
            $contract = $fee * ($company->percentage / 100);
        } else {
            $contract = $company->fixed_fee;
        }

        $driverAmount = ($fee - $contract) * ($driver->driver_percentage / 100);

        $companyAmount = $fee - $contract - $driverAmount;

        return [
            'order_num' => fake()->unique()->numerify('ORD#####'),

            'delivery_fee' => $fee,
            'delivery_fee_base' => $fee * $rate,

            'contract_company_percentage' => $company->percentage,
            'contract_company_fixed' => $company->fixed_fee,

            'contract_company_amount' => $contract,
            'contract_company_amount_base' => $contract * $rate,

            'driver_amount' => $driverAmount,
            'driver_amount_base' => $driverAmount * $rate,

            'company_amount' => $companyAmount,
            'company_amount_base' => $companyAmount * $rate,

            'exchange_rate' => $rate,

            'currency_id' => $currency->id,
            'driver_id' => $driver->id,
            'contract_company_id' => $company->id,

            'created_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}
