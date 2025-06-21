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
        // Run role and permission seeder first
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            SampleDataSeeder::class,
            TestDataSeeder::class, // Add the new test data seeder
        ]);
    }
}
