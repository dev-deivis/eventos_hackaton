@echo off
chcp 65001 > nul
cls
echo.
echo ========================================
echo   Corrección Final - Rankings
echo ========================================
echo.

python fix_rankings_final.py

echo.
pause
