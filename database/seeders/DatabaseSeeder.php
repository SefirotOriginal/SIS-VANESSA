<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        User::updateOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            RolesAndPermissions::class,
            ProductsSeeder::class,

            // Add other seeders here
            AssignSuperUserToTestSeeder::class,
            AssignPermissionsToRolesSeeder::class,
        ]);
    }
}
