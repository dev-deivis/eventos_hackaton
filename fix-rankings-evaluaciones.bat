@echo off
chcp 65001 > nul
cls
echo.
echo ========================================
echo   Corrigiendo Rankings y Evaluaciones
echo ========================================
echo.

python fix_rankings_evaluaciones_juez.py

echo.
pause
