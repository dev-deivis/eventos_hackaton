@echo off
echo ========================================
echo   SOLUCION DE PROBLEMAS SMTP
echo ========================================
echo.

:menu
echo Selecciona una opcion:
echo.
echo 1. Diagnosticar conexion SMTP
echo 2. Cambiar a puerto 465 (SSL)
echo 3. Verificar firewall de Windows
echo 4. Probar con Gmail SMTP (alternativa)
echo 5. Ver logs de Laravel
echo 6. Salir
echo.
set /p opcion="Ingresa el numero (1-6): "

if "%opcion%"=="1" goto diagnostico
if "%opcion%"=="2" goto puerto465
if "%opcion%"=="3" goto firewall
if "%opcion%"=="4" goto gmail
if "%opcion%"=="5" goto logs
if "%opcion%"=="6" goto salir
goto menu

:diagnostico
echo.
echo ===== DIAGNOSTICO DE CONEXION =====
echo.
echo Probando puerto 587...
powershell -Command "Test-NetConnection -ComputerName smtp-relay.brevo.com -Port 587 -InformationLevel Detailed"
echo.
echo Probando puerto 465...
powershell -Command "Test-NetConnection -ComputerName smtp-relay.brevo.com -Port 465 -InformationLevel Detailed"
echo.
pause
goto menu

:puerto465
echo.
echo ===== CAMBIANDO A PUERTO 465 =====
echo.
call cambiar-puerto-465.bat
goto menu

:firewall
echo.
echo ===== VERIFICAR FIREWALL =====
echo.
echo Abriendo configuracion del firewall...
echo Por favor verifica:
echo 1. Que el firewall permita conexiones salientes al puerto 587 o 465
echo 2. Que tu antivirus no este bloqueando la conexion
echo.
start firewall.cpl
pause
goto menu

:gmail
echo.
echo ===== ALTERNATIVA: GMAIL SMTP =====
echo.
echo Si Brevo no funciona, puedes usar Gmail:
echo.
echo 1. Ve a tu cuenta de Gmail
echo 2. Activa la verificacion en 2 pasos
echo 3. Genera una "Contraseña de aplicacion"
echo 4. Configura en .env:
echo    MAIL_MAILER=smtp
echo    MAIL_HOST=smtp.gmail.com
echo    MAIL_PORT=587
echo    MAIL_USERNAME=tu_email@gmail.com
echo    MAIL_PASSWORD=tu_contraseña_de_aplicacion
echo    MAIL_ENCRYPTION=tls
echo.
echo NOTA: Gmail tiene limite de 500 correos/dia
echo.
pause
goto menu

:logs
echo.
echo ===== LOGS DE LARAVEL =====
echo.
echo Mostrando ultimas 30 lineas del log...
echo.
powershell -Command "Get-Content storage\logs\laravel.log -Tail 30"
echo.
pause
goto menu

:salir
echo.
echo ¡Hasta luego!
exit

