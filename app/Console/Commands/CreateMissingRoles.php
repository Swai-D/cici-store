<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateMissingRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing Manager and Cashier roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create Manager role if it doesn't exist
        if (!Role::where('name', 'Manager')->exists()) {
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
            ]);
            $this->info('Manager role created successfully!');
        } else {
            $this->info('Manager role already exists.');
        }

        // Create Cashier role if it doesn't exist
        if (!Role::where('name', 'Cashier')->exists()) {
            $cashierRole = Role::create(['name' => 'Cashier']);
            $cashierRole->givePermissionTo([
                'view_dashboard',
                'view_products',
                'view_sales', 'create_sales',
                'view_expenses', 'create_expenses',
                'view_reports',
                'view_customers', 'create_customers',
            ]);
            $this->info('Cashier role created successfully!');
        } else {
            $this->info('Cashier role already exists.');
        }

        $this->info('All roles are now available!');
        return 0;
    }
}
