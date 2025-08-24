<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupAI extends Command
{
    protected $signature = 'ai:setup {--force : Force run migrations} {--seeder-only : Run only the AI seeder without migrations}';
    protected $description = 'Setup AI Business Consultant for CICI Store';

    public function handle()
    {
        $this->info('🤖 Setting up AI Business Consultant for CICI Store...');
        $this->newLine();

        // Check if we should force migrations
        $force = $this->option('force');
        $seederOnly = $this->option('seeder-only');

        // Run migrations (skip if seeder-only mode)
        if (!$seederOnly) {
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
        } else {
            $this->info('📦 Skipping migrations (seeder-only mode)...');
        }

        // Run seeders safely
        $this->info('🌱 Running seeders...');
        try {
            // Always run SettingSeeder first
            Artisan::call('db:seed', ['--class' => 'SettingSeeder']);
            $this->info('✅ SettingSeeder completed');
            
            // Run AI Permission Seeder (safe for production)
            Artisan::call('db:seed', ['--class' => 'AiPermissionSeeder']);
            $this->info('✅ AiPermissionSeeder completed');
            
            $this->info('✅ All seeders completed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Seeder failed: ' . $e->getMessage());
            $this->error('This might be due to existing permissions. The AI setup may still work.');
            // Don't return 1 here as the setup might still be functional
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
