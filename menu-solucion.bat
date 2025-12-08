@echo off
chcp 65001 >nul
color 0E
cls

:menu
echo ╔════════════════════════════════════════════════════════════╗
echo ║       🔧 SOLUCIONADOR DE PROBLEMAS DE CORREO              ║
echo ║           ERROR: Authentication Failed (535)              ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📋 DIAGNÓSTICO:
echo    Tu API Key de Brevo está incorrecta o tu email no está verificado
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
echo   OPCIONES DE SOLUCIÓN:
echo.
echo   1. 🔑 Regenerar API Key de Brevo (RECOMENDADO)
echo.
echo   2. 📧 Usar Gmail en lugar de Brevo (ALTERNATIVA)
echo.
echo   3. 🧪 Probar envío de correo
echo.
echo   4. 📋 Ver configuración actual
echo.
echo   5. 📖 Ver guía completa
echo.
echo   6. 🚪 Salir
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
set /p opcion="Selecciona una opción (1-6): "

if "%opcion%"=="1" goto brevo
if "%opcion%"=="2" goto gmail
if "%opcion%"=="3" goto prueba
if "%opcion%"=="4" goto config
if "%opcion%"=="5" goto guia
if "%opcion%"=="6" goto salir
echo.
echo ❌ Opción inválida. Intenta de nuevo.
timeout /t 2 >nul
cls
goto menu

:brevo
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  🔑 REGENERAR API KEY DE BREVO                            ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Ejecutando asistente de Brevo...
echo.
pause
call arreglar-brevo.bat
cls
goto menu

:gmail
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  📧 CONFIGURAR GMAIL SMTP                                 ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo ⚠️  IMPORTANTE: Esto reemplazará tu configuración de Brevo
echo.
echo ¿Estás seguro? (S/N)
set /p confirmar="Tu respuesta: "
if /i "%confirmar%" NEQ "S" (
    echo.
    echo ❌ Cancelado
    timeout /t 2 >nul
    cls
    goto menu
)
echo.
call usar-gmail.bat
cls
goto menu

:prueba
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  🧪 PRUEBA DE ENVÍO DE CORREO                             ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Limpiando cache...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
echo ✅ Cache limpiado
echo.
echo Ejecutando prueba...
echo.
php test-brevo-email.php
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
pause
cls
goto menu

:config
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  📋 CONFIGURACIÓN ACTUAL                                  ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Leyendo configuración de .env...
echo.
findstr /B "MAIL_" .env
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
echo 💡 Verificaciones:
echo.
echo    ¿MAIL_USERNAME es tu email verificado?
echo    ¿MAIL_PASSWORD empieza con "xsmtpsib-" (Brevo)?
echo    ¿O es una contraseña de 16 caracteres (Gmail)?
echo.
pause
cls
goto menu

:guia
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  📖 GUÍA COMPLETA DE SOLUCIÓN                             ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Abriendo guía en el navegador...
start regenerar-api-key-brevo.md
echo.
echo ✅ Si no abre, busca el archivo: regenerar-api-key-brevo.md
echo.
echo 📚 Documentos disponibles:
echo    • regenerar-api-key-brevo.md - Brevo paso a paso
echo    • SOLUCION_ERROR_SMTP.md - Todas las soluciones
echo.
pause
cls
goto menu

:salir
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║  👋 ¡HASTA LUEGO!                                         ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 💡 Recuerda:
echo.
echo    • Para volver a ejecutar este menú: .\menu-solucion.bat
echo    • Para probar correos: php test-brevo-email.php
echo    • Para ver logs: type storage\logs\laravel.log
echo.
echo 📧 Si tienes problemas, revisa regenerar-api-key-brevo.md
echo.
timeout /t 3
exit
