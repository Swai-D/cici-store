#!/bin/bash

echo "⏳ Running Laravel setup & server..."

# Clear cached config & routes
php artisan optimize:clear

# Run migrations
php artisan migrate --force

# Start Laravel app
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
