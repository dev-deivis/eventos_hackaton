@echo off
chcp 65001 > nul
cls
echo.
echo ========================================
echo   Aplicando Modo Oscuro - Vistas Usuario
echo ========================================
echo.
echo Ejecutando correcciones...
echo.

python fix_dark_mode.py

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo   ¡Proceso completado exitosamente!
    echo ========================================
) else (
    echo.
    echo ========================================
    echo   Ocurrió un error durante el proceso
    echo ========================================
)

echo.
pause
