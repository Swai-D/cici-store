<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupAI extends Command
{
    protected $signature = 'ai:setup {--force : Force run migrations}';
    protected $description = 'Setup AI Business Consultant for CICI Store';

    public function handle()
    {
        $this->info('🤖 Setting up AI Business Consultant for CICI Store...');
        $this->newLine();

        // Check if we should force migrations
        $force = $this->option('force');

        // Run migrations
        $this->info('📦 Running migrations...');
        try {
            if ($force) {
                Artisan::call('migrate', ['--force' => true]);
            } else {
                Artisan::call('migrate');
            }
            $this->info('✅ Migrations completed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            return 1;
        }

        // Run seeders
        $this->info('🌱 Running seeders...');
        try {
            Artisan::call('db:seed', ['--class' => 'SettingSeeder']);
            
            // Check if roles already exist (to avoid conflicts)
            $adminRole = \Spatie\Permission\Models\Role::where('name', 'Admin')->first();
            if ($adminRole) {
                // Roles exist, just add AI permissions
                Artisan::call('db:seed', ['--class' => 'AiPermissionSeeder']);
            } else {
                // No roles exist, run full role seeder
                $this->info('Creating roles and permissions...');
                Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
            }
            
            $this->info('✅ Seeders completed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Seeder failed: ' . $e->getMessage());
            return 1;
        }

        // Clear caches
        $this->info('🗄️ Clearing caches...');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $this->info('✅ Caches cleared');

        $this->newLine();
        $this->info('🎉 AI Business Consultant setup completed successfully!');
        $this->newLine();
        
        $this->info('🎯 Next steps:');
        $this->line('1. Visit /admin/ai to configure your OpenAI API key');
        $this->line('2. Enable the AI feature');
        $this->line('3. Visit /ai-chat to start using the AI Business Consultant');
        $this->newLine();
        
        $this->info('📚 For more information, see AI_IMPLEMENTATION_GUIDE.md');
        
        return 0;
    }
}
