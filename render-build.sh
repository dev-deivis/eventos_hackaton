#!/usr/bin/env bash
# exit on error
set -o errexit

# Instalar Composer si no existe
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        >&2 echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi
    
    php composer-setup.php --quiet
    rm composer-setup.php
    export PATH="$PATH:$PWD"
    alias composer="php composer.phar"
fi

# Instalar dependencias de Composer
php composer.phar install --no-dev --optimize-autoloader || composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node
npm install

# Compilar assets
npm run build

# Limpiar y optimizar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Opcional: Ejecutar seeders (descomenta si los necesitas)
# php artisan db:seed --force
