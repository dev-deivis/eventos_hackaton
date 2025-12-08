@echo off
cls
echo ================================================
echo   ARREGLAR CREDENCIALES DE BREVO
echo ================================================
echo.
echo Tu API Key de Brevo esta incorrecta o expirada
echo.
echo Sigue estos pasos:
echo.
echo ================================================
echo   PASO 1: Abrir Brevo
echo ================================================
echo.
echo Abriendo https://app.brevo.com/ en tu navegador...
pause
start https://app.brevo.com/
echo.
echo Inicia sesion con: alonsoalmaraz18@gmail.com
echo.
pause
echo.
echo ================================================
echo   PASO 2: Generar nueva API Key
echo ================================================
echo.
echo En Brevo:
echo 1. Click en tu NOMBRE (arriba derecha)
echo 2. Click en "SMTP & API"
echo 3. Pestana "SMTP"
echo 4. Click en "Create a new SMTP key"
echo 5. Nombre: "Laravel Hackathon"
echo 6. Click "Generate"
echo 7. COPIA LA CLAVE COMPLETA (xsmtpsib-...)
echo.
pause
echo.
echo ================================================
echo   PASO 3: Verificar email
echo ================================================
echo.
echo En Brevo:
echo 1. Ve a "Senders"
echo 2. Verifica que alonsoalmaraz18@gmail.com tenga check verde
echo 3. Si NO tiene check verde, click "Verify" y revisa tu correo
echo.
pause
echo.
echo ================================================
echo   PASO 4: Actualizar .env
echo ================================================
echo.
echo Abriendo .env en Notepad...
pause
notepad .env
echo.
echo IMPORTANTE: Actualiza esta linea:
echo MAIL_PASSWORD=xsmtpsib-TU_NUEVA_CLAVE_AQUI
echo.
echo La clave debe empezar con: xsmtpsib-
echo.
echo Guarda el archivo y cierra Notepad
pause
echo.
echo ================================================
echo   PASO 5: Limpiar cache y probar
echo ================================================
echo.
echo Limpiando cache...
php artisan config:clear
php artisan cache:clear
echo.
echo Cache limpiado!
echo.
echo Ahora ejecuta: php test-brevo-email.php
echo.
pause
