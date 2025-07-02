#!/bin/bash

echo "🔧 Fixing assets for Railway deployment..."

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Build assets
echo "📦 Building assets..."
npm run build

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/build/

# Verify assets exist (more flexible)
echo "✅ Verifying assets..."
if ls public/build/assets/*.css 1> /dev/null 2>&1; then
    echo "✅ CSS files found:"
    ls public/build/assets/*.css
else
    echo "❌ CSS files missing"
fi

if ls public/build/assets/*.js 1> /dev/null 2>&1; then
    echo "✅ JS files found:"
    ls public/build/assets/*.js
else
    echo "❌ JS files missing"
fi

# Check manifest
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "✅ Manifest file found"
else
    echo "❌ Manifest file missing"
fi

echo "🎉 Asset setup complete!" 