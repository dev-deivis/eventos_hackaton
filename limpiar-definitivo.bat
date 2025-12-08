@echo off
cls
echo ================================================
echo   SOLUCION DEFINITIVA - LIMPIAR CREDENCIALES
echo ================================================
echo.
echo GitHub sigue detectando credenciales en commits viejos
echo Vamos a usar git filter-repo para limpiar todo
echo.
pause

echo ================================================
echo   OPCION 1: Usar git-filter-repo
echo ================================================
echo.
echo Instalando git-filter-repo...
pip install git-filter-repo --break-system-packages
echo.
pause

echo ================================================
echo   PASO 1: Crear archivo con patrones a eliminar
echo ================================================
echo.
echo Creando archivo de patrones...
(
echo .env.backup.587
echo GUIA_DEPLOY_RAILWAY.md
) > archivos-sensibles.txt
echo.
echo Archivo creado: archivos-sensibles.txt
echo.
pause

echo ================================================
echo   PASO 2: Limpiar historial
echo ================================================
echo.
echo ATENCION: Esto reescribira TODO el historial
echo.
set /p confirmar="¿Continuar? (S/N): "
if /i "%confirmar%" NEQ "S" exit /b 0
echo.
git filter-repo --invert-paths --paths-from-file archivos-sensibles.txt --force
echo.
echo Historial limpio!
echo.
pause

echo ================================================
echo   PASO 3: Reconectar con GitHub
echo ================================================
echo.
echo Agregando remote de nuevo...
git remote add origin https://github.com/dev-deivis/eventos_hackaton.git
echo.
pause

echo ================================================
echo   PASO 4: Push forzado
echo ================================================
echo.
git push --force origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Historial completamente limpio
    echo Push exitoso a GitHub
    echo.
) else (
    echo.
    echo ================================================
    echo   NOTA
    echo ================================================
    echo.
    echo Si falla, intenta la Opcion 2 (mas simple)
    echo.
)

echo.
echo Limpiando archivos temporales...
del archivos-sensibles.txt
echo.
pause
