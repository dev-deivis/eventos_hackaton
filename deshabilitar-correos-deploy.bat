@echo off
echo =============================================
echo  DESHABILITAR CORREOS - DEPLOY A RAILWAY
echo =============================================
echo.

cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"

echo [1/4] Agregando cambios...
git add routes/web.php app/Http/Controllers/EventoController.php CORREOS_DESHABILITADOS.md

echo [2/4] Creando commit...
git commit -m "feat: Deshabilitar correos temporalmente para desarrollo"

echo [3/4] Push a Railway...
git push origin main

echo [4/4] Limpiando archivos temporales...
git status

echo.
echo ========================================
echo   DEPLOY COMPLETADO
echo ========================================
echo.
echo Cambios realizados:
echo  - Ruta /test-email deshabilitada
echo  - Envio de correos en EventoController comentado
echo  - Documentacion creada (CORREOS_DESHABILITADOS.md)
echo.
echo Railway redeploy automaticamente (2-3 min)
echo Ahora puedes seguir desarrollando sin problemas!
echo.
echo Para reactivar correos al final, lee: CORREOS_DESHABILITADOS.md
echo.
pause
