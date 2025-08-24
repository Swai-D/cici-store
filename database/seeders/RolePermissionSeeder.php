<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view_dashboard',
            
            // Products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Sales
            'view_sales',
            'create_sales',
            'edit_sales',
            'delete_sales',
            
            // Expenses
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'delete_expenses',
            
            // Reports
            'view_reports',
            'generate_reports',
            'export_reports',
            
            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',
            
            // Categories
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            // Suppliers
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'delete_suppliers',
            
            // Customers
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            
            // AI Business Consultant
            'manage_ai',
            'use_ai',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => 'Manager']);
        $managerRole->givePermissionTo([
            'view_dashboard',
            'view_products', 'create_products', 'edit_products',
            'view_sales', 'create_sales', 'edit_sales',
            'view_expenses', 'create_expenses', 'edit_expenses',
            'view_reports', 'generate_reports',
            'view_categories', 'create_categories', 'edit_categories',
            'view_suppliers', 'create_suppliers', 'edit_suppliers',
            'view_customers', 'create_customers', 'edit_customers',
            'use_ai', // Can use AI Business Consultant
        ]);

        $cashierRole = Role::create(['name' => 'Cashier']);
        $cashierRole->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_sales', 'create_sales',
            'view_expenses', 'create_expenses',
            'view_reports',
            'view_customers', 'create_customers',
            'use_ai', // Can use AI Business Consultant for business insights
        ]);

        // Admin user will be created by AdminUserSeeder
    }
}
