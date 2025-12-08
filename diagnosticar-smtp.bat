@echo off
echo ========================================
echo   DIAGNOSTICO DE CONEXION SMTP BREVO
echo ========================================
echo.

echo [PASO 1] Verificando configuracion actual...
echo.
php artisan tinker --execute="echo 'MAIL_MAILER: ' . config('mail.default') . PHP_EOL; echo 'MAIL_HOST: ' . config('mail.mailers.smtp.host') . PHP_EOL; echo 'MAIL_PORT: ' . config('mail.mailers.smtp.port') . PHP_EOL; echo 'MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption') . PHP_EOL;"
echo.

echo [PASO 2] Probando conectividad al servidor...
echo.
echo Testeando conexion a smtp-relay.brevo.com:587...
powershell -Command "Test-NetConnection -ComputerName smtp-relay.brevo.com -Port 587"
echo.

echo [PASO 3] Probando puerto alternativo 465...
echo.
powershell -Command "Test-NetConnection -ComputerName smtp-relay.brevo.com -Port 465"
echo.

echo [PASO 4] Verificando DNS...
echo.
nslookup smtp-relay.brevo.com
echo.

echo ========================================
echo   RECOMENDACIONES
echo ========================================
echo.
echo Si el puerto 587 NO es accesible:
echo 1. Prueba con puerto 465 (SSL)
echo    Cambia en .env:
echo    MAIL_PORT=465
echo    MAIL_ENCRYPTION=ssl
echo.
echo 2. Verifica tu firewall/antivirus
echo 3. Prueba desactivando temporalmente el firewall
echo 4. Contacta a tu ISP (puede estar bloqueando el puerto)
echo.
echo Si el puerto 465 tampoco funciona:
echo 1. Tu red/ISP puede estar bloqueando SMTP
echo 2. Intenta desde otra red (hotspot movil)
echo 3. Usa una VPN
echo.

pause
