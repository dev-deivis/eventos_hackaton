@echo off
chcp 65001 >nul
color 0B
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  📧 CONFIGURAR GMAIL SMTP - ALTERNATIVA A BREVO           ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📋 Este script te ayudará a configurar Gmail como proveedor SMTP
echo.
echo ⚠️  REQUISITOS:
echo    - Cuenta de Gmail (alonsoalmaraz18@gmail.com)
echo    - Verificación en 2 pasos activada
echo.
echo 💡 LÍMITES DE GMAIL:
echo    - 500 correos por día (suficiente para desarrollo)
echo    - Gratis para siempre
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 1/6] Activar Verificación en 2 Pasos               ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Abriendo configuración de seguridad de Google...
echo.
pause
start https://myaccount.google.com/security
echo.
echo ✅ Navegador abierto
echo.
echo 📍 En la página de seguridad:
echo.
echo    1. Busca "Verificación en 2 pasos"
echo    2. Si NO está activada: Actívala ahora
echo    3. Si YA está activada: ¡Perfecto! Continúa
echo.
echo ⚠️  SIN verificación en 2 pasos NO podrás generar la contraseña
echo.
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 2/6] Generar Contraseña de Aplicación              ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Abriendo generador de contraseñas...
echo.
pause
start https://myaccount.google.com/apppasswords
echo.
echo ✅ Navegador abierto
echo.
echo 📍 En la página:
echo.
echo    1. Nombre de la app: "Laravel Hackathon"
echo    2. Click "Crear"
echo    3. Google mostrará una contraseña de 16 caracteres
echo    4. Ejemplo: "abcd efgh ijkl mnop"
echo    5. ¡COPIA LA CONTRASEÑA!
echo.
echo 💡 IMPORTANTE: Esta contraseña solo se muestra una vez
echo.
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 3/6] Hacer Backup de .env                          ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Creando backup...
copy .env .env.backup.brevo
echo.
echo ✅ Backup creado: .env.backup.brevo
echo.
echo    Si algo sale mal, puedes restaurar con:
echo    copy .env.backup.brevo .env
echo.
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 4/6] Configurar Gmail en .env                      ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Ingresa la contraseña de aplicación que copiaste
echo (16 caracteres, puede tener espacios, ejemplo: abcd efgh ijkl mnop)
echo.
set /p gmail_password="Contraseña de aplicación: "
echo.

echo Actualizando .env con Gmail...
powershell -Command "(Get-Content .env) -replace 'MAIL_HOST=.*', 'MAIL_HOST=smtp.gmail.com' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) -replace 'MAIL_PORT=.*', 'MAIL_PORT=587' | Set-Content .env"
del .env.tmp

powershell -Command "(Get-Content .env) -replace 'MAIL_ENCRYPTION=.*', 'MAIL_ENCRYPTION=tls' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) -replace 'MAIL_USERNAME=.*', 'MAIL_USERNAME=alonsoalmaraz18@gmail.com' | Set-Content .env"
del .env.tmp

powershell -Command "(Get-Content .env) -replace 'MAIL_PASSWORD=.*', 'MAIL_PASSWORD=%gmail_password%' | Set-Content .env.tmp"
move /y .env.tmp .env >nul

echo.
echo ✅ Configuración actualizada:
echo    - MAIL_HOST=smtp.gmail.com
echo    - MAIL_PORT=587
echo    - MAIL_ENCRYPTION=tls
echo    - MAIL_USERNAME=alonsoalmaraz18@gmail.com
echo    - MAIL_PASSWORD=(tu contraseña de app)
echo.
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 5/6] Limpiar Cache                                 ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Limpiando cache de Laravel...
php artisan config:clear
php artisan cache:clear
echo.
echo ✅ Cache limpiado
echo.
pause
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  [PASO 6/6] Probar Envío de Correo                        ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Ejecutando prueba...
echo.
pause
php test-brevo-email.php
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo ¿El correo se envió exitosamente?
echo.
echo ✅ SI → ¡Gmail configurado correctamente! 🎉
echo.
echo ❌ NO → Verifica:
echo    • Verificación en 2 pasos activada
echo    • Contraseña de aplicación correcta (sin espacios extra)
echo    • Copiaste bien los 16 caracteres
echo.
echo Si necesitas volver a Brevo:
echo    copy .env.backup.brevo .env
echo    php artisan config:clear
echo.
pause
