#!/bin/bash
# Script de build para Laravel + Node en Railpack (corrige EBUSY y Rollup issues)
set -e  # Exit on error

# Function to safely clean directories
safe_clean() {
    local dir="$1"
    if [ -d "$dir" ]; then
        echo "Cleaning $dir..."
        # Try multiple approaches to clean the directory
        (rm -rf "$dir" 2>/dev/null) || \
        (find "$dir" -type f -delete 2>/dev/null && find "$dir" -type d -empty -delete 2>/dev/null) || \
        (chmod -R 755 "$dir" 2>/dev/null && rm -rf "$dir" 2>/dev/null) || \
        echo "Warning: Could not fully clean $dir, continuing..."
    fi
}

# Clean npm cache with better error handling
echo "Cleaning npm cache..."
npm cache clean --force 2>/dev/null || echo "Warning: npm cache clean failed, continuing..."

# Clean problematic cache directories more safely
safe_clean "node_modules/.vite"
safe_clean "node_modules/.cache" 
safe_clean "/app/node_modules/.vite"
safe_clean "/app/node_modules/.cache"

# Install PHP dependencies first
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Fix npm/rollup native dependencies issue
echo "Fixing npm dependencies for build environment..."

# Remove problematic files that can cause native module issues
rm -f package-lock.json 2>/dev/null || true
safe_clean "node_modules"

# Install Node dependencies with proper native module handling
echo "Installing Node.js dependencies (fresh install)..."
npm install --prefer-offline --no-audit --no-fund

# Build assets with better error handling
echo "Building assets..."
npm run build

# Laravel optimizations
echo "Optimizing Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Migrations (safe for prod)
echo "Running migrations..."
php artisan migrate --force

# Storage link for public assets
echo "Creating storage link..."
php artisan storage:link

echo "Build completed successfully!"