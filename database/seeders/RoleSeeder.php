<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'Admin',
            'is_system' => true,
        ]);

        Role::create([
            'name' => 'No Access',
            'is_system' => false,
        ]);


        // Give admin all permissions
        $admin->permissions()->sync(
            Permission::pluck('id')
        );
    }
}
