<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Settlement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Settlement>
 */
class SettlementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $driver = \App\Models\Driver::inRandomOrder()->first();

        $orders = fake()->numberBetween(10, 40);

        $delivery = fake()->randomFloat(2, 200, 1000);

        $driverTotal = $delivery * ($driver->driver_percentage / 100);

        $contract = $delivery * 0.25;

        $company = $delivery - $driverTotal - $contract;

        return [
            'driver_percentage' => $driver->driver_percentage,
            'settlement_num' => fake()->unique()->numerify('SET#####'),
            'driver_id' => $driver->id,
            'date' => fake()->dateTimeBetween('-2 months'),

            'total_orders' => $orders,

            'delivery_total' => $delivery,
            'driver_total' => $driverTotal,

            'subtotal' => $delivery,

            'company_total' => $company,
            'contract_company_total' => $contract,

            'status' => fake()->randomElement([
                'pending',
                'paid',
            ]),

            'notes' => fake()->optional()->sentence(),
        ];
    }
}
