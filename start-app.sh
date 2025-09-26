#!/bin/bash
# Script de inicio para Laravel en Railpack
set -e

# Ensure Node.js is available
export PATH=$PATH:/mise/installs/node/22/bin

# Limpia y optimiza cachés
php artisan optimize:clear
php artisan migrate --force

# Inicia servidor
php artisan serve --host=0.0.0.0 --port=8080