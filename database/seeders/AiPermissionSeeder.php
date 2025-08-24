<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AiPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create AI permissions
        $permissions = [
            'manage_ai' => 'Manage AI settings and configuration',
            'use_ai' => 'Use AI Business Consultant',
        ];

        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Get existing roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();

        // Admin gets ALL permissions (including new AI permissions)
        if ($adminRole) {
            // Admin already has all permissions, so we just need to ensure AI permissions exist
            // The RolePermissionSeeder gives Admin all permissions with Permission::all()
            // So Admin will automatically get these new AI permissions
        }

        // Manager gets use_ai permission (can use AI but not manage settings)
        if ($managerRole) {
            $managerRole->givePermissionTo(['use_ai']);
        }

        // Cashier gets use_ai permission (can use AI for business insights)
        if ($cashierRole) {
            $cashierRole->givePermissionTo(['use_ai']);
        }

        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
