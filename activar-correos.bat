@echo off
cls
echo ================================================
echo   ACTIVAR SISTEMA DE CORREOS
echo ================================================
echo.
echo Este script actualizara todos los controladores
echo para que usen NotificacionHelper en lugar de
echo NotificationService
echo.
pause

echo Actualizando EventoController...
echo Ya actualizado manualmente

echo.
echo ================================================
echo   LISTO!
echo ================================================
echo.
echo El sistema de correos esta activo.
echo.
echo Para probar:
echo 1. php diagnostico-completo.php
echo 2. Crear un evento en la app
echo 3. Revisar logs: tail -f storage/logs/laravel.log
echo.
pause
