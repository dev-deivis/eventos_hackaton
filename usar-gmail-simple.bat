@echo off
cls
echo ================================================
echo   CONFIGURAR GMAIL (ALTERNATIVA A BREVO)
echo ================================================
echo.
echo Gmail es mas facil y rapido de configurar
echo Limite: 500 correos por dia
echo.
pause
echo.
echo ================================================
echo   PASO 1: Activar verificacion en 2 pasos
echo ================================================
echo.
echo Abriendo configuracion de seguridad...
pause
start https://myaccount.google.com/security
echo.
echo En la pagina:
echo 1. Busca "Verificacion en 2 pasos"
echo 2. Si NO esta activada: Activala ahora
echo 3. Si YA esta activada: Continua
echo.
pause
echo.
echo ================================================
echo   PASO 2: Generar contrasena de aplicacion
echo ================================================
echo.
echo Abriendo generador de contrasenas...
pause
start https://myaccount.google.com/apppasswords
echo.
echo En la pagina:
echo 1. Nombre: "Laravel Hackathon"
echo 2. Click "Crear"
echo 3. Google muestra una contrasena de 16 caracteres
echo 4. COPIA LA CONTRASENA (puede tener espacios)
echo.
pause
echo.
echo ================================================
echo   PASO 3: Actualizar .env
echo ================================================
echo.
echo Abriendo .env en Notepad...
pause
notepad .env
echo.
echo IMPORTANTE: Actualiza estas lineas:
echo.
echo MAIL_HOST=smtp.gmail.com
echo MAIL_PORT=587
echo MAIL_ENCRYPTION=tls
echo MAIL_USERNAME=alonsoalmaraz18@gmail.com
echo MAIL_PASSWORD=la_contrasena_de_16_caracteres
echo.
echo NOTA: Quita los espacios de la contrasena
echo Ejemplo: "abcd efgh ijkl mnop" se convierte en "abcdefghijklmnop"
echo.
echo Guarda el archivo y cierra Notepad
pause
echo.
echo ================================================
echo   PASO 4: Limpiar cache y probar
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
