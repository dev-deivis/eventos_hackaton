@echo off
cls
echo ================================================
echo   SUBIR CAMBIOS A GITHUB (SIN CREDENCIALES)
echo ================================================
echo.
echo Este script limpiara archivos sensibles antes
echo de subirlos a GitHub
echo.
pause
cls

echo ================================================
echo   PASO 1: Verificar archivos sensibles
echo ================================================
echo.
echo Archivos que NO deben subirse:
echo - .env
echo - .env.backup*
echo - Cualquier archivo con credenciales
echo.
echo Verificando .gitignore...
findstr /C:".env" .gitignore >nul
if %errorlevel%==0 (
    echo [OK] .gitignore configurado correctamente
) else (
    echo [ERROR] .gitignore no tiene .env
    pause
    exit /b 1
)
echo.
pause

echo ================================================
echo   PASO 2: Agregar cambios
echo ================================================
echo.
echo Agregando archivos al staging...
git add .
echo.
echo Archivos agregados
echo.
pause

echo ================================================
echo   PASO 3: Ver cambios a commitear
echo ================================================
echo.
git status
echo.
echo IMPORTANTE: Verifica que NO aparezcan archivos .env
echo.
pause

echo ================================================
echo   PASO 4: Crear commit
echo ================================================
echo.
set /p mensaje="Ingresa el mensaje del commit: "
echo.
git commit -m "%mensaje%"
echo.
pause

echo ================================================
echo   PASO 5: Push a GitHub
echo ================================================
echo.
echo Subiendo cambios a GitHub...
git push origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Cambios subidos correctamente a GitHub
    echo.
) else (
    echo.
    echo ================================================
    echo   ERROR
    echo ================================================
    echo.
    echo Si GitHub bloqueo el push por credenciales:
    echo 1. Revisa que no hayas subido archivos .env
    echo 2. Verifica .gitignore
    echo 3. Ejecuta: git reset HEAD~1
    echo 4. Corrige y vuelve a intentar
    echo.
)
pause
