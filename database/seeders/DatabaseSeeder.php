<?php
namespace Database\Seeders;
use App\Models\User;
use Database\Factories\CollectionFactory;
use Database\Factories\ContractCompanyFactory;
use Database\Factories\DriverFactory;
use Database\Factories\FinancialTransactionFactory;
use Database\Factories\OrderFactory;
use Database\Factories\SettlementFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            CurrencySeeder::class,
            PaymentMethodSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserAdminSeeder::class
        ]);
        // DriverFactory::new()->count(20)->create();
        // ContractCompanyFactory::new()->count(10)->create();
        // OrderFactory::new()->count(500)->create();
        // SettlementFactory::new()->count(50)->create();
        // CollectionFactory::new()->count(100)->create();
        // FinancialTransactionFactory::new()->count(300)->create();
    }
}
