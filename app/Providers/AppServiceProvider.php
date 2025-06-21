<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\AssetHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade directives for assets
        Blade::directive('viteAsset', function ($expression) {
            return "<?php echo App\Helpers\AssetHelper::viteAsset($expression); ?>";
        });

        Blade::directive('viteCss', function ($expression) {
            return "<?php echo App\Helpers\AssetHelper::css($expression); ?>";
        });

        Blade::directive('viteJs', function ($expression) {
            return "<?php echo App\Helpers\AssetHelper::js($expression); ?>";
        });
    }
}
