#!/bin/bash
# Script de build para Laravel + Node en Railpack (corrige EBUSY)
set -e  # Sale si hay error

# Limpia caché de Vite/npm para evitar locks
rm -rf node_modules/.vite
rm -rf node_modules/.cache

# Instala dependencias PHP
composer install --no-dev --optimize-autoloader --no-interaction

# Instala dependencias Node y compila assets
npm ci --force --omit=dev  # Forza instalación limpia, omite dev deps
npm run build  # Compila CSS/JS (vite build)

# Optimizaciones Laravel
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Migraciones (seguro en prod)
php artisan migrate --force

# Enlace storage (para archivos públicos)
php artisan storage:link