<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with role assignment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $roleName = $this->argument('role');

        // Check if role exists
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' does not exist!");
            $this->info("Available roles: " . Role::pluck('name')->implode(', '));
            return 1;
        }

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email '{$email}' already exists!");
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // Assign role
        $user->assignRole($role);

        $this->info("User '{$name}' created successfully with role '{$roleName}'!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");

        return 0;
    }
}
