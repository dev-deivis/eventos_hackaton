@echo off
cls
color 0B
echo ============================================
echo    PREPARAR PROYECTO PARA RENDER
echo ============================================
echo.
echo Este script preparara tu proyecto para Render.
echo.
pause
cls

echo [1/5] Verificando archivos necesarios...
echo.

if exist "composer.json" (
    echo [OK] composer.json
) else (
    echo [ERROR] No se encuentra composer.json
    pause
    exit
)

if exist "package.json" (
    echo [OK] package.json
) else (
    echo [ERROR] No se encuentra package.json
    pause
    exit
)

if exist ".env.example" (
    echo [OK] .env.example
) else (
    echo [ERROR] No se encuentra .env.example
    pause
    exit
)

echo.
echo [2/5] Limpiando cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear

echo.
echo [3/5] Optimizando para produccion...
call composer install --optimize-autoloader --no-dev
call npm install
call npm run build

echo.
echo [4/5] Verificando Git...
git status

echo.
echo [5/5] Listo para subir a GitHub!
echo.
echo ============================================
echo    PROYECTO LISTO PARA RENDER
echo ============================================
echo.
echo SIGUIENTE PASO:
echo.
echo 1. Crea un repositorio en GitHub:
echo    https://github.com/new
echo.
echo 2. Copia la URL del repositorio
echo.
echo 3. Ejecuta estos comandos:
echo.
echo    git init
echo    git add .
echo    git commit -m "Proyecto listo para Render"
echo    git remote add origin [TU-URL-AQUI]
echo    git branch -M main
echo    git push -u origin main
echo.
echo 4. Ve a Render y sigue la guia:
echo    DESPLEGAR_RENDER_COMPLETO.md
echo.
pause
