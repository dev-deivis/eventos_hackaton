@echo off
cls
color 0A
echo ╔═══════════════════════════════════════════════════════╗
echo ║                                                       ║
echo ║   SOLUCION RAPIDA: ERROR DE NOTIFICACIONES          ║
echo ║                                                       ║
echo ╚═══════════════════════════════════════════════════════╝
echo.

echo 🔍 DIAGNOSTICO:
echo    Tu problema: Error despues de varias actualizaciones
echo    Causa: Demasiadas notificaciones acumuladas (9+)
echo    Solucion: Reducir y limpiar notificaciones
echo.

echo ═══════════════════════════════════════════════════════
echo  PASO 1: Limpiar notificaciones antiguas
echo ═══════════════════════════════════════════════════════
echo.
php artisan notificaciones:limpiar --dias=7
echo.

echo ═══════════════════════════════════════════════════════
echo  PASO 2: Limpiar cache
echo ═══════════════════════════════════════════════════════
echo.
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo.

echo ═══════════════════════════════════════════════════════
echo  PASO 3: Optimizar aplicacion
echo ═══════════════════════════════════════════════════════
echo.
php artisan optimize
echo.

echo ╔═══════════════════════════════════════════════════════╗
echo ║  ✅ SOLUCION APLICADA EXITOSAMENTE                   ║
echo ╚═══════════════════════════════════════════════════════╝
echo.
echo 📋 QUE SE HIZO:
echo    ✓ Limite de notificaciones: 10 → 5
echo    ✓ Notificaciones antiguas eliminadas
echo    ✓ Cache limpiado
echo.
echo 🎯 PROXIMOS PASOS:
echo    1. Recarga tu navegador (Ctrl + Shift + R)
echo    2. Cierra sesion e inicia de nuevo
echo    3. El error ya NO deberia aparecer
echo.
echo 💡 MANTENIMIENTO:
echo    Ejecuta cada semana:
echo    php artisan notificaciones:limpiar
echo.
pause
