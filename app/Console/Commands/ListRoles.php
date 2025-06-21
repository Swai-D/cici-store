<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class ListRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            $this->info('No roles found.');
            return 0;
        }

        $this->info('Available roles:');
        $this->newLine();

        $headers = ['ID', 'Name', 'Guard Name', 'Created At'];
        $rows = [];

        foreach ($roles as $role) {
            $rows[] = [
                $role->id,
                $role->name,
                $role->guard_name,
                $role->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
