#!/bin/bash
# Script de build para Laravel + Node en Railpack (corrige EBUSY en .vite cache)
set -e  # Exit on error

# Clean npm cache safely to avoid EBUSY
npm cache clean --force

# Optional: Attempt to clean .vite and .cache, ignore if busy
rm -rf node_modules/.vite node_modules/.cache /app/node_modules 2>/dev/null || true

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and compile assets
npm ci --force --omit=dev  # Clean install, force to override locks, omit dev deps for prod
npm run build  # Compile styles/CSS/JS (vite build)

# Laravel optimizations
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Migrations (safe for prod)
php artisan migrate --force

# Storage link for public assets
php artisan storage:link