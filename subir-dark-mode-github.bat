@echo off
chcp 65001 > nul
cls
echo.
echo ========================================
echo   SUBIR CAMBIOS DE MODO OSCURO A GITHUB
echo ========================================
echo.
echo Este script:
echo   1. Agregará todos los archivos modificados
echo   2. Creará un commit con mensaje descriptivo
echo   3. Subirá los cambios a GitHub
echo.
echo ⚠️  IMPORTANTE:
echo   - Asegúrate de haber ejecutado "aplicar-dark-mode-usuario.bat" primero
echo   - Asegúrate de haber probado los cambios localmente
echo.
pause
echo.

echo [1/4] Verificando estado actual...
git status
echo.

echo [2/4] Agregando archivos modificados...
git add resources/views/
git add aplicar-dark-mode-usuario.bat
git add fix_dark_mode.py
git add DARK_MODE_USUARIO_IMPLEMENTADO.md
git add INSTRUCCIONES_DARK_MODE.md
echo ✓ Archivos agregados
echo.

echo [3/4] Creando commit...
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

echo [4/4] Subiendo cambios a GitHub...
git push origin main
echo.

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo   ✓ ¡CAMBIOS SUBIDOS EXITOSAMENTE!
    echo ========================================
    echo.
    echo Los cambios están ahora en GitHub
    echo Railway.app los detectará y desplegará automáticamente
    echo.
    echo Próximos pasos:
    echo   1. Espera 2-5 minutos a que Railway despliegue
    echo   2. Verifica el sitio en producción
    echo   3. Avisa a tu equipo de los cambios
    echo.
) else (
    echo.
    echo ========================================
    echo   ⚠️  ERROR AL SUBIR CAMBIOS
    echo ========================================
    echo.
    echo Posibles causas:
    echo   - No estás en la rama correcta
    echo   - Hay conflictos con cambios de tus compañeros
    echo   - No tienes permisos para hacer push
    echo.
    echo Solución:
    echo   1. Abre una terminal aquí
    echo   2. Ejecuta: git status
    echo   3. Ejecuta: git pull origin main
    echo   4. Resuelve conflictos si hay
    echo   5. Intenta de nuevo
    echo.
)

pause
