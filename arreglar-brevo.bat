@echo off
chcp 65001 >nul
color 0A
cls

echo ╔════════════════════════════════════════════════════════════╗
echo ║  🔑 REGENERAR CREDENCIALES DE BREVO - PASO A PASO         ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📋 DIAGNÓSTICO: Error de autenticación detectado (código 535)
echo.
echo ❌ Tu API Key de Brevo está incorrecta, expirada o el email no está verificado
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   PASOS A SEGUIR:
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo [PASO 1/5] Abrir Brevo en el navegador
echo.
echo Voy a abrir https://app.brevo.com/ en tu navegador...
echo Por favor inicia sesión con: alonsoalmaraz18@gmail.com
echo.
pause
start https://app.brevo.com/
echo.
echo ✅ Navegador abierto
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo [PASO 2/5] Generar nueva SMTP Key
echo.
echo 📍 En Brevo, sigue estos pasos:
echo.
echo    1. Click en tu NOMBRE (esquina superior derecha)
echo    2. Click en "SMTP ^& API"
echo    3. Selecciona la pestaña "SMTP"
echo    4. Click en "Create a new SMTP key"
echo    5. Nombre: "Laravel Hackathon Events"
echo    6. Click "Generate"
echo    7. ¡COPIA LA CLAVE COMPLETA!
echo.
echo    Formato: xsmtpsib-XXXXXXXXXXXXX-YYYYYYYYYYY
echo.
pause
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo [PASO 3/5] Verificar email
echo.
echo 📍 En Brevo:
echo.
echo    1. Ve a "Senders" en el menú lateral
echo    2. Busca: alonsoalmaraz18@gmail.com
echo    3. Si tiene ⚠️ naranja: Click "Verify" y revisa tu email
echo    4. Debe mostrar ✅ verde
echo.
pause
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo [PASO 4/5] Actualizar .env
echo.
echo Voy a abrir tu archivo .env en el Bloc de notas...
echo.
pause
notepad .env
echo.
echo 📝 Actualiza estas líneas:
echo.
echo    MAIL_USERNAME=alonsoalmaraz18@gmail.com
echo    MAIL_PASSWORD=xsmtpsib-TU_NUEVA_CLAVE_AQUI
echo.
echo ⚠️  IMPORTANTE:
echo    - MAIL_PASSWORD debe ser la clave que COPIASTE de Brevo
echo    - NO uses tu contraseña de Gmail
echo    - La clave empieza con: xsmtpsib-
echo.
echo ¿Ya actualizaste el .env? Guárdalo y cierra el Bloc de notas
echo.
pause
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo [PASO 5/5] Limpiar cache y probar
echo.
echo Limpiando cache de Laravel...
php artisan config:clear
php artisan cache:clear
echo.
echo ✅ Cache limpiado
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo 🧪 EJECUTANDO PRUEBA DE CORREO...
echo.
pause
php test-brevo-email.php
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

echo ¿El correo se envió exitosamente?
echo.
echo SI → ¡Felicidades! El sistema está funcionando 🎉
echo NO → Revisa estos puntos comunes:
echo.
echo   ❌ Email no verificado en Brevo → Ve a Senders
echo   ❌ API Key mal copiada → Regenera y copia bien (sin espacios)
echo   ❌ Usaste contraseña Gmail → Debe ser clave xsmtpsib-...
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
echo 💡 Si necesitas más ayuda, lee: regenerar-api-key-brevo.md
echo.

pause
