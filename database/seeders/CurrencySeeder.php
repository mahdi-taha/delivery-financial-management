<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Currency::insert([
        [
            'name' => 'Lebanese Pound',
            'symbol' => 'LBP',
            'input_mode' => 'thousands',
            'rate' => 1,
            'rounding_unit' => 10000,
            'is_default' => true,
        ],
        [
            'name' => 'US Dollar',
            'symbol' => 'USD',
            'input_mode' => 'normal',
            'rate' => 89500,
            'rounding_unit' => 1,
            'is_default' => false,
        ],
    ]);
    }
}
