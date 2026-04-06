#!/bin/bash
set -e

echo "Starting deployment..."

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Storage link
php artisan storage:link 2>/dev/null || true

# Run migrations
php artisan migrate --force

echo "Deployment complete!"
