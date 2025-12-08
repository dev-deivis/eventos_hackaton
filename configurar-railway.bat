@echo off
chcp 65001 >nul
cls

echo ================================================
echo   CONFIGURAR CORREOS EN RAILWAY
echo ================================================
echo.
echo Este script te ayudara a configurar las
echo variables de correo en Railway
echo.
pause
cls

echo ================================================
echo   PASO 1: Verificar configuracion local
echo ================================================
echo.
echo Leyendo configuracion de .env local...
echo.
findstr /B "MAIL_" .env
echo.
echo ================================================
echo.
echo IMPORTANTE: Estas son las variables que debes
echo copiar a Railway (exactamente iguales)
echo.
pause
cls

echo ================================================
echo   PASO 2: Abrir Railway
echo ================================================
echo.
echo Abriendo Railway en el navegador...
pause
start https://railway.app/
echo.
echo En Railway:
echo 1. Selecciona tu proyecto "hackathon-events"
echo 2. Click en tu servicio web
echo 3. Ve a la pestana "Variables"
echo.
pause
cls

echo ================================================
echo   PASO 3: Agregar/Actualizar variables
echo ================================================
echo.
echo En la seccion "Variables" de Railway,
echo agrega o actualiza estas variables:
echo.
echo ------------------------------------------------
echo Variable: MAIL_ENABLED
echo Valor: true
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_MAILER
echo Valor: smtp
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_HOST
echo Valor: smtp-relay.brevo.com
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_PORT
echo Valor: 587
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_USERNAME
echo Valor: 9d814c001@smtp-brevo.com
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_PASSWORD
echo Valor: (Tu clave SMTP de Brevo)
echo Copia de tu .env local
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_ENCRYPTION
echo Valor: tls
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_FROM_ADDRESS
echo Valor: alonsoalmaraz18@gmail.com
echo ------------------------------------------------
echo.
echo ------------------------------------------------
echo Variable: MAIL_FROM_NAME
echo Valor: Hackathon Events
echo ------------------------------------------------
echo.
pause
cls

echo ================================================
echo   PASO 4: Deploy automatico
echo ================================================
echo.
echo Una vez que guardes las variables,
echo Railway hara un redeploy automatico
echo.
echo Espera 2-3 minutos para que termine el deploy
echo.
echo Puedes ver el progreso en:
echo - Pestana "Deployments"
echo - Los logs apareceran en tiempo real
echo.
pause
cls

echo ================================================
echo   PASO 5: Verificar en produccion
echo ================================================
echo.
echo Para probar que funciona en Railway:
echo.
echo 1. Ve a tu app en produccion:
echo    https://web-production-ef44a.up.railway.app/
echo.
echo 2. Realiza una accion que envie correo:
echo    - Crea un evento nuevo (si eres admin)
echo    - Solicita unirte a un equipo
echo    - etc.
echo.
echo 3. Revisa los logs en Railway:
echo    - Pestana "Logs"
echo    - Busca: "Correo enviado exitosamente"
echo.
pause
cls

echo ================================================
echo   RESUMEN
echo ================================================
echo.
echo Variables a configurar en Railway:
echo.
echo MAIL_ENABLED=true
echo MAIL_MAILER=smtp
echo MAIL_HOST=smtp-relay.brevo.com
echo MAIL_PORT=587
echo MAIL_USERNAME=9d814c001@smtp-brevo.com
echo MAIL_PASSWORD=(tu clave SMTP)
echo MAIL_ENCRYPTION=tls
echo MAIL_FROM_ADDRESS=alonsoalmaraz18@gmail.com
echo MAIL_FROM_NAME=Hackathon Events
echo.
echo ================================================
echo.
echo Despues de configurar:
echo 1. Railway hace redeploy automatico
echo 2. Espera 2-3 minutos
echo 3. Prueba en produccion
echo 4. Verifica logs
echo.
echo LISTO!
echo.
pause
