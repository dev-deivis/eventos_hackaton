@echo off
cls
echo ================================================
echo   VERIFICAR CONFIGURACION DE RAILWAY
echo ================================================
echo.
echo Para verificar que Railway tiene las variables:
echo.
echo 1. Ve a: https://railway.app/
echo 2. Tu proyecto: hackathon-events
echo 3. Pestana: Variables
echo.
echo DEBE tener estas 9 variables:
echo.
echo [ ] MAIL_ENABLED=true
echo [ ] MAIL_MAILER=smtp
echo [ ] MAIL_HOST=smtp-relay.brevo.com
echo [ ] MAIL_PORT=587
echo [ ] MAIL_USERNAME=9d814c001@smtp-brevo.com
echo [ ] MAIL_PASSWORD=xsmtpsib-...
echo [ ] MAIL_ENCRYPTION=tls
echo [ ] MAIL_FROM_ADDRESS=alonsoalmaraz18@gmail.com
echo [ ] MAIL_FROM_NAME=Hackathon Events
echo.
echo Si falta alguna, agregala.
echo Si estan todas, Railway debe hacer redeploy.
echo.
pause

echo ================================================
echo   VERIFICAR LOGS
echo ================================================
echo.
echo Ahora ve a la pestana "Logs" en Railway
echo.
echo Busca uno de estos mensajes:
echo - "Correo enviado exitosamente"
echo - "Error al enviar correo"
echo - "MAIL_ENABLED"
echo.
echo Los logs te diran exactamente que esta pasando
echo.
pause
