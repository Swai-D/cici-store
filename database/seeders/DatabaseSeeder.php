<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Production: Only essential data
        $this->call([
            RolePermissionSeeder::class,    // Creates roles and permissions
            AdminUserSeeder::class,         // Creates admin user
        ]);
        
        // Development: Uncomment below for dummy data during development
        $this->call([
            CategorySeeder::class,         // Dummy categories
            SupplierSeeder::class,         // Dummy suppliers
            SampleDataSeeder::class,       // Sample products/sales
            TestDataSeeder::class,         // Test data
        ]);
    }
}
