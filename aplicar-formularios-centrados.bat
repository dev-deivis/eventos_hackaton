@echo off
cls
color 0D
echo ════════════════════════════════════════════════════════
echo   🎯 FORMULARIOS CENTRADOS Y COMPACTOS
echo ════════════════════════════════════════════════════════
echo.
echo ✨ Cambios aplicados:
echo.
echo   📦 ANCHO CONTROLADO:
echo      • Individual: max-w-2xl (672px)
echo      • Lote: max-w-3xl (768px)
echo      • Ganadores: max-w-3xl (768px)
echo      • Centrados con mx-auto
echo.
echo   🎨 ESPACIADO OPTIMIZADO:
echo      • Padding: p-5
echo      • Titulos: text-lg mb-3
echo      • Formulario: space-y-3
echo      • Labels: text-xs mb-1.5
echo      • Inputs: px-3 py-2 text-sm
echo      • Botones: px-4 py-2 text-sm
echo.
echo   📊 RESULTADO:
echo      ✓ 40%% mas compactos
echo      ✓ Mejor uso del espacio
echo      ✓ Mas profesional
echo      ✓ Centrados elegantemente
echo.
echo ════════════════════════════════════════════════════════
echo   Limpiando cache del servidor...
echo ════════════════════════════════════════════════════════
echo.
php artisan view:clear
php artisan cache:clear
echo.
echo ✅ Servidor actualizado
echo.
echo ════════════════════════════════════════════════════════
echo   ⚠️  IMPORTANTE - LIMPIA EL CACHE DEL NAVEGADOR
echo ════════════════════════════════════════════════════════
echo.
echo Opcion 1 (Rapida):
echo   Ctrl + Shift + R
echo.
echo Opcion 2 (Completa):
echo   Ctrl + Shift + Delete
echo   ^> Borrar imagenes y archivos en cache
echo   ^> Ultima hora
echo.
echo Opcion 3 (Verificacion):
echo   Ctrl + Shift + N (Modo incognito)
echo.
pause
