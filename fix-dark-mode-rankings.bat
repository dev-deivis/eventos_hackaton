@echo off
echo ====================================
echo CORRIGIENDO MODO OSCURO EN RANKINGS
echo ====================================
echo.

echo ✅ Correcciones aplicadas en admin/rankings.blade.php:
echo.
echo   1. Titulo "Rankings de Equipos" ahora visible
echo   2. Subtitulo "Clasificacion General" ahora visible
echo   3. Boton "Limpiar" con mejor contraste
echo   4. Select dropdown con fondo oscuro
echo   5. Texto del nombre del equipo visible
echo   6. Labels de criterios (Innovacion, Presentacion, etc) visibles
echo   7. Barras de progreso con fondo oscuro
echo   8. Borders de cards mejorados
echo.

echo 🎨 Cambios aplicados:
echo   - text-gray-900 → text-gray-900 dark:text-white
echo   - text-gray-700 → text-gray-700 dark:text-gray-300
echo   - bg-gray-200 → bg-gray-200 dark:bg-gray-600
echo   - border-gray-200 → border-gray-200 dark:border-gray-700
echo.

echo 🌙 ANTES (Modo Oscuro):
echo   ❌ Textos negros invisibles sobre fondo oscuro
echo   ❌ Boton "Limpiar" gris claro no legible
echo   ❌ Labels de barras no visibles
echo.

echo ✨ DESPUES (Modo Oscuro):
echo   ✅ Todos los textos legibles con buen contraste
echo   ✅ Boton "Limpiar" oscuro con texto blanco
echo   ✅ Labels claramente visibles
echo   ✅ Barras de progreso con fondo apropiado
echo.

echo 📍 Archivos modificados:
echo   - resources/views/admin/rankings.blade.php
echo.

echo 🔄 Comandos a ejecutar:
echo.
echo   php artisan cache:clear
echo   php artisan view:clear
echo   npm run build
echo.

echo ¿Deseas ejecutar los comandos de limpieza? (S/N)
set /p respuesta=

if /i "%respuesta%"=="S" (
    echo.
    echo Ejecutando comandos...
    echo.
    
    call php artisan cache:clear
    call php artisan view:clear
    call npm run build
    
    echo.
    echo ✅ COMPLETADO - Por favor recarga la pagina en el navegador
) else (
    echo.
    echo ⚠️  Recuerda ejecutar los comandos manualmente
)

echo.
echo ====================================
echo MODO OSCURO CORREGIDO
echo ====================================
pause
