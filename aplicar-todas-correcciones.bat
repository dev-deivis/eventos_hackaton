@echo off
cls
echo ========================================
echo   CORRECCIONES MODO OSCURO APLICADAS
echo ========================================
echo.
echo ✅ Constancias corregidas
echo ✅ Usuarios corregidos  
echo ✅ Rankings corregidos
echo.
echo Limpiando cache...
php artisan view:clear
php artisan cache:clear
echo.
echo ✅ Listo! Recarga con Ctrl+Shift+R
pause
