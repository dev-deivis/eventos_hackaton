@echo off
echo ========================================
echo ACTUALIZACION PHP 8.3 Y EXPORTACIONES
echo ========================================
echo.

echo [1/5] Actualizando composer.lock con nuevas dependencias...
composer update --no-dev --optimize-autoloader
if errorlevel 1 (
    echo ERROR: Fallo al actualizar dependencias
    pause
    exit /b 1
)

echo.
echo [2/5] Limpiando cache de configuracion...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo [3/5] Compilando assets...
call npm run build
if errorlevel 1 (
    echo ERROR: Fallo al compilar assets
    pause
    exit /b 1
)

echo.
echo [4/5] Agregando cambios a Git...
git add .
git commit -m "feat: Activar exportaciones PDF y Excel con PHP 8.3"

echo.
echo [5/5] Subiendo a GitHub (auto-deploy a Railway)...
git push origin main

echo.
echo ========================================
echo DEPLOY COMPLETADO!
echo ========================================
echo.
echo Railway detectara los cambios y:
echo 1. Instalara PHP 8.3
echo 2. Instalara phpspreadsheet y dompdf
echo 3. Recompilara el proyecto
echo.
echo Verifica en: https://railway.app
echo.
pause
