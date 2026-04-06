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

# Storage link (try symlink, create manually if it fails on shared hosting)
php artisan storage:link 2>/dev/null || {
    echo "Symlink failed. Creating manual link for shared hosting..."
    rm -rf public/storage
    ln -s ../storage/app/public public/storage 2>/dev/null || cp -r storage/app/public public/storage
}

# Run migrations
php artisan migrate --force

# Seed if database is empty
php artisan db:seed --force 2>/dev/null || true

echo "Deployment complete!"
