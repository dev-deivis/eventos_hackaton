@echo off
cls
echo ================================================
echo   ARREGLAR PUSH BLOQUEADO POR GITHUB
echo ================================================
echo.
echo GitHub bloqueo tu push porque detectó credenciales
echo Vamos a solucionarlo
echo.
pause

echo ================================================
echo   PASO 1: Remover archivo .env.backup.587
echo ================================================
echo.
git rm --cached .env.backup.587
echo.
echo Archivo removido del staging
echo.
pause

echo ================================================
echo   PASO 2: Actualizar .gitignore
echo ================================================
echo.
echo .gitignore ya fue actualizado para ignorar:
echo - .env.*
echo - .env.backup*
echo.
pause

echo ================================================
echo   PASO 3: Commit de los cambios
echo ================================================
echo.
git add .
git commit -m "fix: Remover credenciales sensibles y actualizar gitignore"
echo.
pause

echo ================================================
echo   PASO 4: Push a GitHub
echo ================================================
echo.
echo Intentando push de nuevo...
git push origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Push exitoso a GitHub
    echo Credenciales protegidas correctamente
    echo.
) else (
    echo.
    echo ================================================
    echo   TODAVIA HAY PROBLEMAS
    echo ================================================
    echo.
    echo Opciones:
    echo 1. Ve a GitHub y permite el secreto manualmente
    echo 2. O regenera tu API Key de Brevo
    echo.
    echo Para regenerar:
    echo - Ve a Brevo
    echo - Genera nueva clave SMTP
    echo - Actualiza .env local
    echo - Actualiza Railway
    echo - NO subas a GitHub
    echo.
)
pause
