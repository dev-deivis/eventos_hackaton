#!/usr/bin/env bash

# Este script inicia el worker de Laravel para procesar jobs en background
# Se ejecuta automáticamente en Railway cuando se despliega

echo "🚀 Iniciando Laravel Queue Worker..."

# Procesar jobs infinitamente
# --sleep=3: Espera 3 segundos entre checks
# --tries=3: Reintentar 3 veces si falla
# --max-time=3600: Reiniciar worker cada hora
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
