@echo off
cls
echo =========================================
echo   CORRECCIONES MODO OSCURO APLICADAS
echo =========================================
echo.
echo Archivos corregidos:
echo   ✓ admin/rankings.blade.php
echo   ✓ admin/usuarios/index.blade.php
echo   ✓ admin/usuarios/create.blade.php
echo   ✓ admin/usuarios/edit.blade.php
echo.
echo Limpiando cache...
php artisan view:clear
php artisan cache:clear
echo.
echo ✅ Listo! Recarga tu navegador con Ctrl+Shift+R
echo.
pause
