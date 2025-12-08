@echo off
echo ========================================
echo   CAMBIAR A PUERTO 465 (SSL)
echo ========================================
echo.

echo Este script cambiara la configuracion de SMTP
echo de puerto 587 (TLS) a puerto 465 (SSL)
echo.
echo Esto suele funcionar cuando el puerto 587 esta bloqueado
echo.
pause

echo.
echo [1/3] Haciendo backup de .env...
copy .env .env.backup.587
echo ✅ Backup creado: .env.backup.587
echo.

echo [2/3] Cambiando a puerto 465...
powershell -Command "(Get-Content .env) -replace 'MAIL_PORT=587', 'MAIL_PORT=465' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'MAIL_ENCRYPTION=tls', 'MAIL_ENCRYPTION=ssl' | Set-Content .env"
echo ✅ Cambio realizado
echo.

echo [3/3] Limpiando cache...
php artisan config:clear
php artisan cache:clear
echo ✅ Cache limpiado
echo.

echo ========================================
echo   CAMBIO COMPLETADO
echo ========================================
echo.
echo Configuracion actualizada a:
echo - MAIL_PORT=465
echo - MAIL_ENCRYPTION=ssl
echo.
echo Ahora ejecuta:
echo   php test-brevo-email.php
echo.
echo Si necesitas volver a puerto 587:
echo   copy .env.backup.587 .env
echo.

pause
