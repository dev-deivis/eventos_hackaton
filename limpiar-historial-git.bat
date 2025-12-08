@echo off
cls
echo ================================================
echo   LIMPIAR HISTORIAL DE GIT
echo ================================================
echo.
echo ATENCION: Esto reescribira el historial de Git
echo para remover las credenciales del commit anterior
echo.
echo Que va a pasar:
echo 1. Se desharan los ultimos 2 commits
echo 2. Los archivos sensibles se ignoraran
echo 3. Se creara un nuevo commit limpio
echo 4. Se hara push forzado
echo.
set /p confirmar="¿Continuar? (S/N): "
if /i "%confirmar%" NEQ "S" exit /b 0
echo.
pause

echo ================================================
echo   PASO 1: Deshacer ultimos 2 commits
echo ================================================
echo.
echo Deshaciendo commits pero manteniendo cambios...
git reset --soft HEAD~2
echo.
echo Commits deshechos
echo.
pause

echo ================================================
echo   PASO 2: Remover archivos sensibles
echo ================================================
echo.
echo Removiendo .env.backup.587 si existe...
if exist .env.backup.587 (
    del .env.backup.587
    echo Archivo .env.backup.587 eliminado
) else (
    echo Archivo ya no existe
)
echo.
pause

echo ================================================
echo   PASO 3: Verificar .gitignore
echo ================================================
echo.
echo .gitignore actualizado para ignorar:
findstr /C:".env" .gitignore
echo.
pause

echo ================================================
echo   PASO 4: Crear nuevo commit limpio
echo ================================================
echo.
echo Agregando archivos...
git add .
echo.
echo Creando commit...
git commit -m "feat: Sistema de correos implementado con Brevo (sin credenciales)"
echo.
pause

echo ================================================
echo   PASO 5: Push forzado
echo ================================================
echo.
echo ATENCION: Esto sobrescribira el historial remoto
echo.
set /p confirmar2="¿Hacer push forzado? (S/N): "
if /i "%confirmar2%" NEQ "S" (
    echo.
    echo Push cancelado
    echo Puedes hacerlo manualmente con: git push --force origin main
    pause
    exit /b 0
)
echo.
git push --force origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Historial limpio y subido a GitHub
    echo Credenciales eliminadas del historial
    echo.
) else (
    echo.
    echo ================================================
    echo   ERROR
    echo ================================================
    echo.
    echo El push forzado fallo
    echo Intenta manualmente: git push --force origin main
    echo.
)
pause
