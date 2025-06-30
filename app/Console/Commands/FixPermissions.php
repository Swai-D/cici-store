<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user permissions and roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking and fixing permissions...');

        // Check if roles exist
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();

        if (!$adminRole) {
            $this->error('Admin role not found!');
            return 1;
        }

        if (!$managerRole) {
            $this->error('Manager role not found!');
            return 1;
        }

        if (!$cashierRole) {
            $this->error('Cashier role not found!');
            return 1;
        }

        $this->info('Roles found: Admin, Manager, Cashier');

        // Check permissions
        $permissions = [
            'view_dashboard',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'delete_suppliers',
            'view_sales',
            'create_sales',
            'edit_sales',
            'delete_sales',
            'view_expenses',
            'create_expenses',
            'edit_expenses',
            'delete_expenses',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if (!$perm) {
                $this->error("Permission '{$permission}' not found!");
                return 1;
            }
        }

        $this->info('All permissions found');

        // Check users and their roles
        $users = User::all();
        foreach ($users as $user) {
            $this->info("User: {$user->name} ({$user->email})");
            $roles = $user->roles->pluck('name');
            $this->info("  Roles: " . ($roles->count() > 0 ? $roles->implode(', ') : 'None'));
            
            if ($roles->count() === 0) {
                $this->warn("  No roles assigned! Assigning Admin role...");
                $user->assignRole($adminRole);
                $this->info("  Assigned Admin role to {$user->name}");
            }

            // Check permissions
            $userPermissions = $user->getAllPermissions()->pluck('name');
            $this->info("  Permissions: " . ($userPermissions->count() > 0 ? $userPermissions->implode(', ') : 'None'));
        }

        $this->info('Permission check completed!');
        return 0;
    }
} 