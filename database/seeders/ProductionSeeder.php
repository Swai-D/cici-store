<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's database for production.
     */
    public function run(): void
    {
        // Only essential data for production
        $this->call([
            RolePermissionSeeder::class,    // Creates roles and permissions
            AdminUserSeeder::class,         // Creates admin user
        ]);
    }
} 