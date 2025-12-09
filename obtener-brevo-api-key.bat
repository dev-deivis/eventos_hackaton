@echo off
cls
echo ================================================
echo   OBTENER API KEY DE BREVO
echo ================================================
echo.
echo La API Key es DIFERENTE a la SMTP Key
echo.
echo PASOS:
echo.
echo 1. Ve a: https://app.brevo.com/
echo.
echo 2. Click en tu nombre (esquina superior derecha)
echo.
echo 3. SMTP ^& API
echo.
echo 4. Pestana: "API Keys" (NO "SMTP")
echo.
echo 5. Si no tienes una API Key, click en:
echo    "Create a new API Key"
echo.
echo 6. Nombre: "Laravel Production API"
echo.
echo 7. COPIA la API Key (empieza con: xkeysib-)
echo.
echo 8. Agregala en Railway:
echo    Variables -^> BREVO_API_KEY=tu_clave_aqui
echo.
echo 9. Tambien agregala en .env local:
echo    BREVO_API_KEY=tu_clave_aqui
echo.
pause

start https://app.brevo.com/settings/keys/api
