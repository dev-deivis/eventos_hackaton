@echo off
cls
color 0E
echo ════════════════════════════════════════════════════════
echo   🎯 SOLUCION: Ganadores Automatico Sigue Grande
echo ════════════════════════════════════════════════════════
echo.
echo ❌ PROBLEMA:
echo    El formulario de Ganadores Automatico se ve grande
echo.
echo ✅ CAUSA:
echo    Cache del navegador mostrando version antigua
echo.
echo ════════════════════════════════════════════════════════
echo   PASO 1: Limpiar Cache del Servidor
echo ════════════════════════════════════════════════════════
echo.
php artisan view:clear
php artisan cache:clear
php artisan config:clear
echo.
echo ✅ Cache del servidor limpiado
echo.
echo ════════════════════════════════════════════════════════
echo   PASO 2: Limpiar Cache del Navegador (IMPORTANTE)
echo ════════════════════════════════════════════════════════
echo.
echo 🌐 Opcion 1 - Recarga Forzada (RAPIDO):
echo.
echo    Chrome/Edge/Brave:
echo    → Presiona: Ctrl + Shift + R
echo    → O: Ctrl + F5
echo.
echo    Firefox:
echo    → Presiona: Ctrl + Shift + R
echo    → O: Ctrl + F5
echo.
echo ════════════════════════════════════════════════════════
echo.
echo 🌐 Opcion 2 - Borrar Cache Completo (SEGURO):
echo.
echo    1. Presiona: Ctrl + Shift + Delete
echo    2. Selecciona: "Imagenes y archivos en cache"
echo    3. Periodo: "Ultima hora" o "Todo"
echo    4. Click: "Borrar datos"
echo    5. Recarga la pagina: F5
echo.
echo ════════════════════════════════════════════════════════
echo.
echo 🌐 Opcion 3 - Modo Incognito (TEMPORAL):
echo.
echo    1. Presiona: Ctrl + Shift + N (Chrome/Edge)
echo    2. O: Ctrl + Shift + P (Firefox)
echo    3. Abre tu sitio en modo incognito
echo    4. Verifica que se vea bien
echo.
echo ════════════════════════════════════════════════════════
echo.
echo 📊 VERIFICACION:
echo.
echo    El codigo YA esta corregido con:
echo    ✓ p-6 (en lugar de p-8)
echo    ✓ mb-4 (en lugar de mb-6)
echo    ✓ space-y-4 (en lugar de space-y-6)
echo    ✓ py-2.5 (en lugar de py-3)
echo    ✓ gap-3 (en lugar de gap-4)
echo.
echo    Solo necesitas limpiar el cache del navegador!
echo.
echo ════════════════════════════════════════════════════════
pause
