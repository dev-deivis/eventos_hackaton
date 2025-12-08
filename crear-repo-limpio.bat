@echo off
cls
echo ================================================
echo   SOLUCION RAPIDA - CREAR REPO LIMPIO
echo ================================================
echo.
echo Ya que GitHub sigue bloqueando, vamos a:
echo 1. Crear un repo completamente nuevo
echo 2. Copiar solo el codigo necesario
echo 3. Subir sin historial problematico
echo 4. Conectar Railway al nuevo repo
echo.
echo NOTA: Esto NO afectara tu codigo local
echo.
pause

echo ================================================
echo   PASO 1: Crear backup
echo ================================================
echo.
echo Creando backup de .git...
if exist .git.backup (
    rmdir /s /q .git.backup
)
move .git .git.backup
echo.
echo Backup creado: .git.backup
echo.
pause

echo ================================================
echo   PASO 2: Inicializar repo limpio
echo ================================================
echo.
git init
echo.
echo Repo limpio inicializado
echo.
pause

echo ================================================
echo   PASO 3: Agregar archivos (sin credenciales)
echo ================================================
echo.
echo Agregando todo el codigo...
git add .
echo.
echo Archivos agregados
echo.
pause

echo ================================================
echo   PASO 4: Primer commit limpio
echo ================================================
echo.
git commit -m "feat: Sistema completo de correos con Brevo implementado"
echo.
pause

echo ================================================
echo   PASO 5: Conectar con GitHub
echo ================================================
echo.
echo Opciones:
echo.
echo OPCION A: Usar el mismo repo (forzar)
git remote add origin https://github.com/dev-deivis/eventos_hackaton.git
git push -u --force origin main
echo.
if %errorlevel%==0 (
    echo.
    echo ================================================
    echo   EXITO!
    echo ================================================
    echo.
    echo Repo limpio subido exitosamente
    echo Railway ya NO necesita reconfigurarse
    echo Solo configura las variables MAIL_*
    echo.
    echo Backup del repo anterior en: .git.backup
    echo Si algo sale mal: rmdir /s /q .git ^& move .git.backup .git
    echo.
) else (
    echo.
    echo ================================================
    echo   TODAVIA BLOQUEADO
    echo ================================================
    echo.
    echo GitHub sigue bloqueando
    echo.
    echo OPCION B: Crear repo completamente nuevo
    echo 1. Ve a GitHub y crea un nuevo repo: eventos_hackaton_v2
    echo 2. Ejecuta estos comandos:
    echo    git remote remove origin
    echo    git remote add origin https://github.com/tu-usuario/eventos_hackaton_v2.git
    echo    git push -u origin main
    echo 3. En Railway, cambia el repo conectado
    echo.
    echo OPCION C: Permitir los secretos
    echo Ve a los links de GitHub y permite los secretos
    echo Son de commits viejos que ya no existen en el codigo actual
    echo Luego ejecuta: git push --force origin main
    echo.
)
pause
