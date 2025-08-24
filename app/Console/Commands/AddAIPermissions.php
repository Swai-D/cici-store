<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddAIPermissions extends Command
{
    protected $signature = 'ai:permissions';
    protected $description = 'Add AI permissions to existing roles';

    public function handle()
    {
        $this->info('🔐 Adding AI permissions to existing roles...');
        $this->newLine();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create AI permissions
        $this->info('Creating AI permissions...');
        $permissions = [
            'manage_ai' => 'Manage AI settings and configuration',
            'use_ai' => 'Use AI Business Consultant',
        ];

        foreach ($permissions as $permission => $description) {
            $perm = Permission::firstOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'web',
            ]);
            $this->line("✅ Created permission: {$permission}");
        }

        // Get existing roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();

        if ($adminRole) {
            // Admin gets all permissions (including new AI ones)
            $adminRole->givePermissionTo(['manage_ai', 'use_ai']);
            $this->info("✅ Admin role updated with AI permissions");
        } else {
            $this->warn("⚠️ Admin role not found");
        }

        if ($managerRole) {
            $managerRole->givePermissionTo(['use_ai']);
            $this->info("✅ Manager role updated with use_ai permission");
        } else {
            $this->warn("⚠️ Manager role not found");
        }

        if ($cashierRole) {
            $cashierRole->givePermissionTo(['use_ai']);
            $this->info("✅ Cashier role updated with use_ai permission");
        } else {
            $this->warn("⚠️ Cashier role not found");
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->newLine();
        $this->info('🎉 AI permissions added successfully!');
        $this->newLine();
        
        $this->info('📋 Permission Summary:');
        $this->line('• Admin: manage_ai, use_ai (full access)');
        $this->line('• Manager: use_ai (can use AI, cannot manage settings)');
        $this->line('• Cashier: use_ai (can use AI for business insights)');
        
        return 0;
    }
}
