@echo off
cls
color 0C
echo ════════════════════════════════════════════════════════
echo   🔥 LIMPIEZA COMPLETA Y FORZADA
echo ════════════════════════════════════════════════════════
echo.
echo Eliminando TODO el cache...
echo.

echo [1/6] Limpiando vistas compiladas...
php artisan view:clear

echo [2/6] Limpiando cache de aplicacion...
php artisan cache:clear

echo [3/6] Limpiando cache de configuracion...
php artisan config:clear

echo [4/6] Limpiando cache de rutas...
php artisan route:clear

echo [5/6] Limpiando cache de eventos...
php artisan event:clear

echo [6/6] Optimizando autoloader...
composer dump-autoload

echo.
echo ════════════════════════════════════════════════════════
echo   ✅ LIMPIEZA COMPLETA
echo ════════════════════════════════════════════════════════
echo.
echo 📌 IMPORTANTE - Haz esto en tu navegador:
echo.
echo    1. Presiona Ctrl + Shift + Delete
echo    2. Selecciona "Imagenes y archivos en cache"
echo    3. Selecciona "Solo ultima hora"
echo    4. Click en "Borrar datos"
echo.
echo    O simplemente:
echo    - Chrome/Edge: Ctrl + Shift + R
echo    - Firefox: Ctrl + F5
echo.
echo ════════════════════════════════════════════════════════
pause
