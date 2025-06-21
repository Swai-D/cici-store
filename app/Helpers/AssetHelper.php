<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

class AssetHelper
{
    /**
     * Get the correct asset URL for CSS and JS files
     * Falls back to Vite manifest in production if Vite is not available
     */
    public static function viteAsset($entry)
    {
        // In development, use Vite
        if (app()->environment('local', 'development')) {
            return Vite::asset($entry);
        }

        // In production, try to get from manifest
        $manifestPath = public_path('build/manifest.json');
        
        if (File::exists($manifestPath)) {
            $manifest = json_decode(File::get($manifestPath), true);
            
            if (isset($manifest[$entry])) {
                return asset('build/' . $manifest[$entry]['file']);
            }
        }

        // Fallback to direct asset path
        return asset('build/assets/' . basename($entry));
    }

    /**
     * Get CSS asset URL
     */
    public static function css($entry = 'resources/css/app.css')
    {
        return self::viteAsset($entry);
    }

    /**
     * Get JS asset URL
     */
    public static function js($entry = 'resources/js/app.js')
    {
        return self::viteAsset($entry);
    }
} 