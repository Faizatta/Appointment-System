<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'faiizatta@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'), 
            ]
        );

        // Call Roles and Permissions Seeder
        $this->call(RolesAndAdminSeeder::class);

        // Assign Admin role to the user (optional)
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }
    }
}
