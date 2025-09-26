#!/bin/bash
# Script de build para Laravel en Railpack (compila estilos y evita DB)
set -e  # Exit on error

echo "🚀 Iniciando proceso de build..."

# Ensure Node.js is available
export PATH=$PATH:/mise/installs/node/22/bin
echo "📦 Versión de Node.js:"
node --version
echo "📦 Versión de NPM:"
npm --version

# Limpiar cachés con mejor manejo de errores
echo "🧹 Limpiando cachés..."
npm cache clean --force || true
rm -rf /tmp/.npm/_cacache || true

# Limpiar directorios con manejo seguro
echo "🧹 Limpiando directorios temporales..."
safe_clean() {
    local dir="$1"
    if [ -d "$dir" ]; then
        echo "Limpiando $dir..."
        rm -rf "$dir" 2>/dev/null || find "$dir" -type f -delete || true
    fi
}

safe_clean "node_modules/.vite"
safe_clean "node_modules/.cache"
safe_clean "/app/node_modules/.vite"
safe_clean "/app/node_modules/.cache"

# Instalar dependencias PHP
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Configurar npm para evitar errores
echo "⚙️ Configurando npm..."
npm config set cache /tmp/.npm
npm config set prefer-offline true

# Instalar dependencias Node y compilar assets con mejor manejo de errores
echo "📦 Instalando dependencias Node.js..."
export ROLLUP_SKIP_NATIVE=true
export ADBLOCK=true
export DISABLE_OPENCOLLECTIVE=true

npm ci --no-audit --no-fund || npm install --no-audit --no-fund
echo "🔨 Compilando assets..."
npm run build

# Verificar y mover manifest.json
echo "📝 Verificando manifest.json..."
if [ -f public/build/.vite/manifest.json ]; then
    mv public/build/.vite/manifest.json public/build/
    echo "✅ manifest.json movido correctamente"
fi

if [ ! -f public/build/manifest.json ]; then
    echo "❌ Error: manifest.json no se generó en public/build!"
    exit 1
fi

# Optimizaciones de Laravel
echo "⚡ Optimizando Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enlaces simbólicos de storage
echo "🔗 Creando enlaces simbólicos..."
php artisan storage:link

echo "✅ Build completado exitosamente!"