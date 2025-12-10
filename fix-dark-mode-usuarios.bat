@echo off
echo ==========================================
echo CORRIGIENDO MODO OSCURO EN GESTION DE USUARIOS
echo ==========================================
echo.

echo ✅ Correcciones aplicadas en admin/usuarios/index.blade.php:
echo.
echo   1. Nombres de usuarios ahora visibles (text-gray-900 → text-white)
echo   2. Emails visibles (text-gray-900 → text-gray-300)
echo   3. Badges de roles con fondo oscuro apropiado
echo   4. Carreras y fechas visibles (text-gray-500 → text-gray-400)
echo   5. Avatar inicial con fondo oscuro
echo   6. Botones "Editar" y "Eliminar" con mejor contraste
echo   7. Hover de filas mejorado
echo   8. Dividers y borders oscuros
echo   9. Estado vacio con textos visibles
echo  10. Paginacion con fondo apropiado
echo.

echo 🎨 Cambios de colores aplicados:
echo.
echo   TEXTOS:
echo   - Nombres: dark:text-white
echo   - Emails: dark:text-gray-300
echo   - Carreras/Fechas: dark:text-gray-400
echo.
echo   BADGES DE ROLES:
echo   - Admin: dark:bg-red-900/30 dark:text-red-300
echo   - Juez: dark:bg-purple-900/30 dark:text-purple-300
echo   - Participante: dark:bg-blue-900/30 dark:text-blue-300
echo.
echo   BOTONES:
echo   - Editar: dark:bg-indigo-900/30 dark:text-indigo-300
echo   - Eliminar: dark:bg-red-900/30 dark:text-red-300
echo.
echo   TABLA:
echo   - Header: dark:bg-gray-700
echo   - Body: dark:bg-gray-800
echo   - Hover: dark:hover:bg-gray-700
echo   - Dividers: dark:divide-gray-700
echo   - Borders: dark:border-gray-700
echo.

echo 🌙 ANTES (Modo Oscuro):
echo   ❌ Nombres de usuarios invisibles (negro sobre oscuro)
echo   ❌ Emails invisibles
echo   ❌ Carreras invisibles
echo   ❌ Fechas invisibles
echo   ❌ Badges de roles con fondo claro
echo   ❌ Avatar con fondo muy claro
echo.

echo ✨ DESPUES (Modo Oscuro):
echo   ✅ Nombres claramente visibles en blanco
echo   ✅ Emails legibles en gris claro
echo   ✅ Carreras y fechas visibles
echo   ✅ Badges con fondo translucido apropiado
echo   ✅ Avatar con fondo oscuro
echo   ✅ Excelente contraste en todos los elementos
echo.

echo 📍 Archivos modificados:
echo   - resources/views/admin/usuarios/index.blade.php
echo.

echo 📊 Elementos corregidos:
echo   - Nombre usuario (10 correcciones)
echo   - Email usuario (10 correcciones)
echo   - Badges de roles (30 correcciones: admin/juez/participante)
echo   - Carrera (10 correcciones)
echo   - Fecha registro (10 correcciones)
echo   - Avatar circular (10 correcciones)
echo   - Botones Editar (10 correcciones)
echo   - Botones Eliminar (10 correcciones)
echo   - Estructura tabla (thead, tbody, dividers)
echo   - Estado vacio
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
    echo ✅ COMPLETADO - Recarga la pagina en el navegador (Ctrl+Shift+R)
    echo.
    echo 🎯 Verifica estos elementos en /admin/usuarios:
    echo    - Nombres de usuarios en blanco
    echo    - Emails en gris claro
    echo    - Badges de roles con fondos oscuros
    echo    - Carreras y fechas visibles
    echo    - Botones Editar/Eliminar con buen contraste
) else (
    echo.
    echo ⚠️  Recuerda ejecutar los comandos manualmente:
    echo.
    echo    php artisan cache:clear
    echo    php artisan view:clear  
    echo    npm run build
)

echo.
echo ==========================================
echo MODO OSCURO CORREGIDO EN USUARIOS
echo ==========================================
pause
