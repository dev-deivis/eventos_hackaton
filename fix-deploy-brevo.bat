@echo off
echo ================================
echo  DEPLOY A RAILWAY - BREVO SMTP
echo ================================
echo.

cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"

echo [1/3] Agregando cambios...
git add routes/web.php config/services.php

echo [2/3] Commit...
git commit -m "Fix: Cambiar de Resend a Brevo SMTP para emails"

echo [3/3] Push a Railway...
git push origin main

echo.
echo ✅ DEPLOY INICIADO
echo.
echo Espera 2-3 minutos y luego prueba:
echo https://web-production-ef44a.up.railway.app/test-email
echo.
pause
