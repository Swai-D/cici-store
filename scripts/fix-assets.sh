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

# Verify assets exist
echo "✅ Verifying assets..."
if [ -f "public/build/assets/app-7dFzyK7f.css" ]; then
    echo "✅ CSS file found"
else
    echo "❌ CSS file missing"
fi

if [ -f "public/build/assets/app-DNxiirP_.js" ]; then
    echo "✅ JS file found"
else
    echo "❌ JS file missing"
fi

echo "🎉 Asset setup complete!" 