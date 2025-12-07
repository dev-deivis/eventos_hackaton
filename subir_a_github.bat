@echo off
cls
echo ============================================
echo    SUBIR PROYECTO A GITHUB
echo ============================================
echo.
echo Este script te ayudara a subir tu proyecto.
echo.
echo PASOS:
echo.
echo 1. Crear repositorio en GitHub:
echo    - Ve a: https://github.com/new
echo    - Nombre: eventos_hackaton
echo    - Visibilidad: Public o Private
echo    - NO marques "Initialize with README"
echo    - Clic en "Create repository"
echo.
echo 2. Copiar la URL del repositorio
echo    Ejemplo: https://github.com/tu-usuario/eventos_hackaton.git
echo.
pause
echo.
echo Inicializando Git...
git init
echo.
echo Agregando archivos...
git add .
echo.
echo Haciendo commit...
git commit -m "Initial commit - Sistema de eventos y constancias"
echo.
echo AHORA PEGA LA URL DE TU REPOSITORIO:
set /p REPO_URL="URL del repositorio: "
echo.
echo Conectando con GitHub...
git remote add origin %REPO_URL%
echo.
echo Subiendo archivos...
git branch -M main
git push -u origin main
echo.
echo ============================================
echo    PROYECTO SUBIDO A GITHUB!
echo ============================================
echo.
echo Ya puedes crear el proyecto en Render.
echo.
pause
