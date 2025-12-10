@echo off
cls
color 0B
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║   ✅ CORRECCIONES MODO OSCURO COMPLETADAS            ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo 📋 Archivos Corregidos:
echo.
echo   ✓ Rankings
echo   ✓ Usuarios (Index, Create, Edit)
echo   ✓ Constancias (Index)
echo   ✓ Constancias (Plantillas)
echo   ✓ Constancias (Generar Nuevas)
echo.
echo ════════════════════════════════════════════════════════
echo.
echo 🧹 Limpiando cache...
php artisan view:clear
php artisan cache:clear
echo.
echo ✅ Cache limpiado
echo.
echo ════════════════════════════════════════════════════════
echo.
echo 🎉 TODO LISTO!
echo.
echo 📌 Próximos pasos:
echo    1. Recarga tu navegador con Ctrl + Shift + R
echo    2. Verifica el modo oscuro en todas las vistas
echo    3. ¡Disfruta del modo oscuro perfecto!
echo.
pause
