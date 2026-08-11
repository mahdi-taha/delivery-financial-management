<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            [
                'name' => 'Cash',
                'is_active' => 1,
                'is_default' => 1,
            ],
            [
                'name' => 'Bank Transfer',
                'is_active' => 1,
                'is_default' => 0,
            ],
            [
                'name' => 'OMT',
                'is_active' => 1,
                'is_default' => 0,
            ],
            [
                'name' => 'Whish',
                'is_active' => 1,
                'is_default' => 0,
            ],
            [
                'name' => 'Cheque',
                'is_active' => 1,
                'is_default' => 0,
            ],
        ]);
    }
}
