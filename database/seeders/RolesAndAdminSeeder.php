<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndAdminSeeder extends Seeder
{
    public function run()
    {

        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web']

        );

        // Permissions
        $permissions = [
            'manage doctor', 'add doctor',  'delete doctor',
            'manage patient', 'add patient',  'delete patient', 'bulk delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'faiizatta@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // Assign role
        $admin->assignRole($adminRole);
    }
}
