@echo off
echo ========================================
echo EJECUTANDO MIGRACION DE EVALUACIONES
echo ========================================
echo.

cd /d "%~dp0"

echo Ejecutando migracion...
php artisan migrate --path=database/migrations/2024_12_01_050000_recreate_evaluaciones_table.php --force

echo.
echo ========================================
echo MIGRACION COMPLETADA
echo ========================================
pause
