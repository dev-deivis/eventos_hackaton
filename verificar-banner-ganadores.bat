@echo off
echo.
echo ============================================
echo   VERIFICACION BANNER DE GANADORES
echo ============================================
echo.

echo [1/3] Verificando archivo NotificationService.php...
findstr /C:"Titulos especiales para ganadores" "app\Services\NotificationService.php" >nul 2>&1
if %errorlevel% == 0 (
    echo [OK] NotificationService actualizado correctamente
) else (
    echo [ERROR] NotificationService no tiene los cambios
)

echo.
echo [2/3] Verificando archivo show.blade.php...
findstr /C:"Banner de Ganador" "resources\views\equipos\show.blade.php" >nul 2>&1
if %errorlevel% == 0 (
    echo [OK] Vista de equipo actualizada correctamente
) else (
    echo [ERROR] Vista de equipo no tiene los cambios
)

echo.
echo [3/3] Verificando documentacion...
if exist "IMPLEMENTACION_BANNER_GANADORES.md" (
    echo [OK] Documentacion creada
) else (
    echo [ERROR] Falta documentacion
)

echo.
echo ============================================
echo   COMO PROBAR
echo ============================================
echo.
echo 1. Ejecuta: php artisan serve
echo 2. Ve a: http://localhost:8000/admin/constancias/generar-nuevas
echo 3. Tab: "Ganadores Automatico"
echo 4. Selecciona evento con evaluaciones
echo 5. Genera constancias
echo 6. Login como participante ganador
echo 7. Ve a: Mis Equipos ^> Tu equipo ganador
echo 8. DEBE APARECER: Banner con confetti
echo.
echo ============================================
echo.

pause
