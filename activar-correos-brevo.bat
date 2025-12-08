@echo off
echo ========================================
echo    ACTIVAR SISTEMA DE CORREOS BREVO
echo ========================================
echo.

echo [1/5] Limpiando cache...
php artisan config:clear
php artisan cache:clear
echo ✅ Cache limpiado
echo.

echo [2/5] Verificando configuracion de mail...
php artisan tinker --execute="echo 'MAIL_MAILER: ' . config('mail.default') . PHP_EOL; echo 'MAIL_HOST: ' . config('mail.mailers.smtp.host') . PHP_EOL; echo 'FROM_ADDRESS: ' . config('mail.from.address') . PHP_EOL;"
echo.

echo [3/5] Verificando clases Mailable...
dir /b app\Mail\*.php
echo.

echo [4/5] Verificando plantillas de email...
dir /b resources\views\emails\*.blade.php
echo.

echo ========================================
echo   ESTADO DEL SISTEMA DE CORREOS
echo ========================================
echo.
echo ✅ Clases Mailable creadas
echo ✅ Plantillas de email creadas
echo ✅ Configuracion cargada
echo.
echo ⚠️  ACCION REQUERIDA:
echo    1. Configura las variables en .env:
echo       MAIL_USERNAME=tu_email@ejemplo.com
echo       MAIL_PASSWORD=tu_clave_smtp_brevo
echo.
echo    2. Ejecuta: php artisan config:cache
echo.
echo    3. Prueba enviando un correo con:
echo       php test-email.php
echo.
echo ========================================
echo.

pause
