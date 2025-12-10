@echo off
cls
echo ============================================
echo   SOLUCION AL PROBLEMA DE NOTIFICACIONES
echo ============================================
echo.
echo 🔧 Problema: Error de sesion despues de varias actualizaciones
echo 📝 Causa: Acumulacion excesiva de notificaciones
echo.
echo ✅ Soluciones aplicadas:
echo.
echo   1. Reducido limite de notificaciones de 10 a 5
echo   2. Creado comando de limpieza automatica
echo   3. Creado middleware para prevenir acumulacion
echo.
echo ============================================
echo.

echo 🧹 PASO 1: Limpiar notificaciones antiguas
echo.
php artisan notificaciones:limpiar
echo.

echo 🔄 PASO 2: Limpiar cache
echo.
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.

echo ✅ PASO 3: Optimizar sesiones
echo.
php artisan session:table
echo.

echo ============================================
echo   COMANDOS DISPONIBLES
echo ============================================
echo.
echo   Limpiar notificaciones (30 dias):
echo   php artisan notificaciones:limpiar
echo.
echo   Limpiar notificaciones (15 dias):
echo   php artisan notificaciones:limpiar --dias=15
echo.
echo   Limpiar notificaciones (7 dias):
echo   php artisan notificaciones:limpiar --dias=7
echo.
echo ============================================
echo   PROGRAMAR LIMPIEZA AUTOMATICA
echo ============================================
echo.
echo Para programar limpieza automatica diaria:
echo.
echo 1. Abre: app/Console/Kernel.php
echo 2. Agrega en schedule():
echo    $schedule-^>command('notificaciones:limpiar')-^>daily();
echo.
echo ============================================
echo   RECOMENDACIONES
echo ============================================
echo.
echo ✓ Ejecutar limpieza semanal
echo ✓ Marcar notificaciones como leidas periodicamente
echo ✓ Evitar acumular mas de 20 notificaciones
echo.
echo ============================================
echo.
echo ✅ SOLUCION COMPLETADA
echo.
pause
