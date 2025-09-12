<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    public function run()
    {

        $adminRole = Role::firstOrCreate(['name' => 'admin']);


        $permissions = [
            'view doctor', 'add doctor', 'update doctor', 'delete doctor',
            'view patient', 'add patient', 'update patient', 'delete patient',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $adminRole->syncPermissions(Permission::all());


        $admin = User::firstOrCreate(
            ['email' => 'faiizatta@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]
        );


        $admin->assignRole($adminRole);
    }
}
