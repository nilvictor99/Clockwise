#!/bin/bash
# Script de build para Laravel en Railpack (compila estilos y evita DB)
set -e  # Exit on error

# Ensure Node.js is available
export PATH=$PATH:/mise/installs/node/22/bin
npm --version  # Debug: Verify npm is available

# Clean npm cache
npm cache clean --force

# Clean node_modules, ignore if busy
rm -rf node_modules/.vite node_modules/.cache /app/node_modules 2>/dev/null || true

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and compile assets
npm ci --force --omit=dev
npm run build || { echo "npm run build failed"; cat /root/.npm/_logs/*.log; exit 1; }

# Move manifest.json to correct location
if [ -f public/build/.vite/manifest.json ]; then
  mv public/build/.vite/manifest.json public/build/
  echo "Moved manifest.json to public/build/"
fi
if [ ! -f public/build/manifest.json ]; then
  echo "Error: manifest.json not generated in public/build!"
  exit 1
fi

# Laravel optimizations (no DB)
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link