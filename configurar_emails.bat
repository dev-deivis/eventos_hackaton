@echo off
cls
echo ============================================
echo    CONFIGURACION DE EMAILS - BREVO
echo ============================================
echo.
echo Este script te ayudara a configurar los emails.
echo.
echo PASOS A SEGUIR:
echo.
echo 1. CONFIGURAR BREVO (https://brevo.com):
echo    - Crear cuenta gratuita
echo    - Ir a Settings ^> SMTP ^& API
echo    - Crear nueva clave SMTP
echo    - Verificar remitente: eventos.contacto.web@gmail.com
echo.
echo 2. CONFIGURACION LOCAL:
echo    Ya esta configurado en .env
echo.
echo 3. PROBAR LOCALMENTE:
php artisan config:clear
php artisan cache:clear
echo.
echo 4. CONFIGURAR EN RENDER:
echo    Variables de entorno a agregar:
echo.
echo    MAIL_MAILER=smtp
echo    MAIL_HOST=smtp-relay.brevo.com
echo    MAIL_PORT=587
echo    MAIL_USERNAME=eventos.contacto.web@gmail.com
echo    MAIL_PASSWORD=lxxx gyrq bgrn ubty
echo    MAIL_ENCRYPTION=tls
echo    MAIL_FROM_ADDRESS=eventos.contacto.web@gmail.com
echo    MAIL_FROM_NAME=Eventos Hackathon TecNM
echo.
echo ============================================
echo.
echo ARCHIVOS CREADOS:
echo [OK] app/Mail/BienvenidaMail.php
echo [OK] app/Mail/NuevoEventoMail.php
echo [OK] app/Mail/SolicitudUnionEquipoMail.php
echo [OK] app/Mail/AceptadoEnEquipoMail.php
echo [OK] resources/views/emails/*.blade.php
echo [OK] .env actualizado
echo.
echo SIGUIENTE PASO:
echo 1. Lee: INTEGRACION_EMAILS_PASO_A_PASO.md
echo 2. Integra el codigo en tus controladores
echo 3. Prueba localmente
echo 4. Configura Render
echo 5. Despliega!
echo.
pause
