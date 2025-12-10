@echo off
chcp 65001 > nul
cls
echo.
echo ========================================
echo   CREAR RAMA Y SUBIR CAMBIOS (Recomendado)
echo ========================================
echo.
echo Este método es MÁS SEGURO para trabajo en equipo:
echo   1. Crea una nueva rama "feature/dark-mode-usuario"
echo   2. Sube los cambios a esa rama
echo   3. Tus compañeros pueden revisar antes de mezclar
echo.
pause
echo.

echo [1/6] Verificando rama actual...
git branch
echo.

echo [2/6] Obteniendo últimos cambios...
git pull origin main
echo ✓ Repositorio actualizado
echo.

echo [3/6] Creando nueva rama...
git checkout -b feature/dark-mode-usuario
echo ✓ Rama creada y cambiada
echo.

echo [4/6] Agregando archivos modificados...
git add resources/views/
git add aplicar-dark-mode-usuario.bat
git add fix_dark_mode.py
git add DARK_MODE_USUARIO_IMPLEMENTADO.md
git add INSTRUCCIONES_DARK_MODE.md
git add subir-dark-mode-github.bat
git add subir-dark-mode-rama.bat
echo ✓ Archivos agregados
echo.

echo [5/6] Creando commit...
git commit -m "feat: Implementar modo oscuro completo en vistas de usuario

- Se agregó soporte completo de dark mode para todas las vistas de usuario/alumno
- Corregidos fondos blancos (bg-white -> bg-white dark:bg-gray-800)
- Corregidos todos los textos para ser legibles en modo oscuro
- Corregidos badges y estados con colores apropiados
- Agregado script automatizado para aplicar cambios (aplicar-dark-mode-usuario.bat)
- Documentación completa en DARK_MODE_USUARIO_IMPLEMENTADO.md

Vistas corregidas:
- Dashboard de usuario
- Lista de eventos
- Seleccionar evento para crear equipo
- Y todas las demás vistas de usuario mediante script automatizado

Mantiene consistencia visual con las vistas de Admin y Juez."
echo ✓ Commit creado
echo.

echo [6/6] Subiendo rama a GitHub...
git push origin feature/dark-mode-usuario
echo.

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo   ✓ ¡RAMA SUBIDA EXITOSAMENTE!
    echo ========================================
    echo.
    echo Próximos pasos:
    echo   1. Ve a GitHub: https://github.com/tu-repo/eventos_hackaton
    echo   2. Verás un botón "Compare & pull request"
    echo   3. Crea el Pull Request
    echo   4. Tus compañeros pueden revisar los cambios
    echo   5. Una vez aprobado, haz merge a main
    echo   6. Railway desplegará automáticamente
    echo.
    echo Ventajas de este método:
    echo   ✓ Tus compañeros pueden revisar antes
    echo   ✓ No rompes el código en main
    echo   ✓ Puedes hacer más cambios si necesario
    echo   ✓ Historial más limpio y organizado
    echo.
) else (
    echo.
    echo ========================================
    echo   ⚠️  ERROR AL SUBIR RAMA
    echo ========================================
    echo.
    echo Intenta manualmente:
    echo   git status
    echo   git pull origin main
    echo   git push origin feature/dark-mode-usuario
    echo.
)

pause
