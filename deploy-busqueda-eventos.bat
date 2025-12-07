@echo off
echo ================================================
echo  DEPLOY: Sistema Busqueda y Filtros de Eventos
echo ================================================
echo.

cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"

echo [1/4] Agregando archivos...
git add app/Http/Controllers/EventoController.php
git add routes/web.php
git add resources/views/admin/eventos/index.blade.php
git add resources/views/admin/dashboard.blade.php
git add SISTEMA_BUSQUEDA_FILTROS_EVENTOS.md

echo [2/4] Creando commit...
git commit -m "feat: Sistema de busqueda y filtros para eventos admin

- Nuevo metodo indexAdmin() en EventoController
- Vista completa admin/eventos/index.blade.php (303 lineas)
- Busqueda por nombre/descripcion (ILIKE case-insensitive)
- Filtros por estado (todos, proximo, en_curso, finalizado)
- Estadisticas en tiempo real (4 metricas)
- Acciones rapidas: Dashboard, Editar, Eliminar
- Paginacion con query string preservado
- Estado vacio con CTA
- Actualizado dashboard admin: boton 'Gestionar Eventos'

Ahora admin puede:
✅ Ver TODOS los eventos sin importar estado
✅ Buscar eventos perdidos
✅ Filtrar por estado facilmente
✅ Ver estadisticas completas
✅ Acceso rapido a gestion"

echo [3/4] Push a Railway...
git push origin main

echo [4/4] Esperando deploy...
timeout /t 3 >nul

echo.
echo ========================================
echo   DEPLOY COMPLETADO
echo ========================================
echo.
echo Cambios implementados:
echo  ✅ Busqueda de eventos
echo  ✅ Filtros por estado
echo  ✅ Estadisticas dashboard
echo  ✅ Ver TODOS los eventos
echo  ✅ Gestion centralizada
echo.
echo Railway redeploy automaticamente (2-3 min)
echo.
echo Prueba la nueva funcionalidad en:
echo https://web-production-ef44a.up.railway.app/eventos/admin/gestionar
echo.
echo Para testing local:
echo php artisan serve
echo Luego: http://localhost:8000/eventos/admin/gestionar
echo.
pause
