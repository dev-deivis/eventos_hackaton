@echo off
echo ========================================
echo VER LOGS DE RAILWAY EN TIEMPO REAL
echo ========================================
echo.
echo Este comando mostrara los logs de Railway
echo donde puedes ver los errores detallados
echo sin necesidad de activar APP_DEBUG=true
echo.
echo Presiona Ctrl+C para detener
echo.
pause

railway logs --tail
