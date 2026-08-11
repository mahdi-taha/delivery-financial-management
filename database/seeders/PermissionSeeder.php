<?php
namespace Database\Seeders;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'dashboard.index', 'group' => 'Dashboard'],
            ['name' => 'drivers.index', 'group' => 'Drivers'],
            ['name' => 'drivers.view', 'group' => 'Drivers'],
            ['name' => 'drivers.create', 'group' => 'Drivers'],
            ['name' => 'drivers.edit', 'group' => 'Drivers'],
            ['name' => 'drivers.delete', 'group' => 'Drivers'],
            ['name' => 'partners.index', 'group' => 'Partners'],
            ['name' => 'partners.create', 'group' => 'Partners'],
            ['name' => 'partners.edit', 'group' => 'Partners'],
            ['name' => 'partners.delete', 'group' => 'Partners'],
            ['name' => 'collections.index', 'group' => 'Collections'],
            ['name' => 'collections.create', 'group' => 'Collections'],
            ['name' => 'collections.pay', 'group' => 'Collections'],
            ['name' => 'collections.delete', 'group' => 'Collections'],
            ['name' => 'transactions.index', 'group' => 'Transactions'],
            ['name' => 'transactions.create', 'group' => 'Transactions'],
            ['name' => 'transactions.cancel', 'group' => 'Transactions'],
            ['name' => 'settlements.index', 'group' => 'Settlements'],
            ['name' => 'settlements.create', 'group' => 'Settlements'],
            ['name' => 'settlements.edit', 'group' => 'Settlements'],
            ['name' => 'settlements.delete', 'group' => 'Settlements'],
            ['name' => 'settlements.view', 'group' => 'Settlements'],
            ['name' => 'settlements.pay', 'group' => 'Settlements'],
            ['name' => 'reports.drivers', 'group' => 'Reports'],
            ['name' => 'reports.partners', 'group' => 'Reports'],
            ['name' => 'reports.company', 'group' => 'Reports'],
            ['name' => 'reports.settlement', 'group' => 'Reports'],
            ['name' => 'reports.transaction', 'group' => 'Reports'],
            ['name' => 'company_info.index', 'group' => 'Company_Info'],
            ['name' => 'company_info.edit', 'group' => 'Company_Info'],
            ['name' => 'currencies.index', 'group' => 'Currencies'],
            ['name' => 'currencies.create', 'group' => 'Currencies'],
            ['name' => 'currencies.edit', 'group' => 'Currencies'],
            ['name' => 'currencies.delete', 'group' => 'Currencies'],
            ['name' => 'payment_methods.index', 'group' => 'Payment_methods'],
            ['name' => 'payment_methods.create', 'group' => 'Payment_methods'],
            ['name' => 'payment_methods.edit', 'group' => 'Payment_methods'],
            ['name' => 'payment_methods.delete', 'group' => 'Payment_methods'],
            ['name' => 'activity_logs', 'group' => 'Activity_logs'],
            ['name' => 'roles.index', 'group' => 'Roles'],
            ['name' => 'roles.create', 'group' => 'Roles'],
            ['name' => 'roles.edit', 'group' => 'Roles'],
            ['name' => 'roles.delete', 'group' => 'Roles'],
            ['name' => 'users.index', 'group' => 'Users'],
            ['name' => 'users.create', 'group' => 'Users'],
            ['name' => 'users.edit', 'group' => 'Users'],
            ['name' => 'users.delete', 'group' => 'Users'],
        ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
