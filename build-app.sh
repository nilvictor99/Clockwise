#!/bin/bash
# Script de build para Laravel + Node en Railpack
set -e  # Sale si hay error

# Instala dependencias PHP
composer install --no-dev --optimize-autoloader

# Instala dependencias Node y compila assets
npm ci  # Más rápido y consistente que npm i en CI/CD
npm run build  # Compila CSS/JS (usa vite build en package.json)

# Optimizaciones Laravel
php artisan optimize:clear  # Limpia caché viejo
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Migraciones (solo en producción)
php artisan migrate --force

# Enlace storage (si usas archivos públicos)
php artisan storage:link
