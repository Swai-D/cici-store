<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users with their roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('roles')->get();

        if ($users->isEmpty()) {
            $this->info('No users found.');
            return 0;
        }

        $this->info('Users in the system:');
        $this->newLine();

        $headers = ['ID', 'Name', 'Email', 'Roles', 'Created At'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(', ') ?: 'None',
                $user->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
