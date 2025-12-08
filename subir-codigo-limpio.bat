@echo off
cls
echo ================================================
echo   SUBIR CODIGO A GITHUB (SIN CREDENCIALES)
echo ================================================
echo.
echo Vamos a subir solo el codigo necesario para
echo que Railway pueda enviar correos
echo.
pause

echo ================================================
echo   PASO 1: Ver estado actual
echo ================================================
echo.
git status
echo.
pause

echo ================================================
echo   PASO 2: Agregar solo archivos de codigo
echo ================================================
echo.
echo Agregando clases Mailable...
git add app/Mail/
echo.
echo Agregando plantillas de correo...
git add resources/views/emails/
echo.
echo Agregando helper actualizado...
git add app/Helpers/NotificacionHelper.php
echo.
echo Agregando .gitignore actualizado...
git add .gitignore
echo.
echo Agregando documentacion (sin credenciales)...
git add GUIA_DEPLOY_RAILWAY.md
git add SISTEMA_CORREOS_IMPLEMENTADO.md
git add RESUMEN_CORREOS_BREVO.md
git add CHECKLIST_ACTIVACION_CORREOS.md
git add GUIA_CONFIGURACION_BREVO.md
echo.
echo Archivos agregados
echo.
pause

echo ================================================
echo   PASO 3: Ver que se va a commitear
echo ================================================
echo.
git status
echo.
echo VERIFICAR: NO deben aparecer archivos .env
echo.
pause

echo ================================================
echo   PASO 4: Crear commit
echo ================================================
echo.
git commit -m "feat: Sistema completo de correos con Brevo - 6 tipos de notificaciones"
echo.
pause

echo ================================================
echo   PASO 5: Intentar push
echo ================================================
echo.
echo Intentando push...
git push origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Codigo subido a GitHub exitosamente
    echo Railway hara redeploy automatico en 2-3 min
    echo.
    echo SIGUIENTE PASO:
    echo 1. Espera el redeploy de Railway
    echo 2. Configura las variables MAIL_* en Railway
    echo 3. Prueba en produccion
    echo.
) else (
    echo.
    echo ================================================
    echo   BLOQUEADO POR GITHUB
    echo ================================================
    echo.
    echo GitHub sigue bloqueando por commits anteriores
    echo.
    echo OPCION 1: Permitir secretos temporalmente
    echo Ve a los links que GitHub te dio y permite los secretos
    echo Luego: git push origin main
    echo Luego: Regenera API Key en Brevo
    echo.
    echo OPCION 2: Crear repo nuevo
    echo Ejecuta: crear-repo-limpio.bat
    echo.
)
pause
