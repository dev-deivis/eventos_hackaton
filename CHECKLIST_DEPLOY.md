# ✅ Checklist de Pre-Deploy

Verifica estos puntos antes de desplegar:

## 📦 Archivos preparados
- [x] render-build.sh creado
- [x] render.yaml creado
- [x] Procfile creado
- [x] .env.render creado como ejemplo
- [x] GUIA_RENDER.md con instrucciones completas

## 🔍 Verificaciones del código

### 1. Migraciones
- [x] Todas usan Laravel Blueprint (compatible con PostgreSQL)
- [x] No hay tipos de datos específicos de SQLite
- [ ] **ACCIÓN**: Ejecutar `php artisan migrate:fresh` localmente para verificar

### 2. Variables de entorno
Tu app usa principalmente:
- ✅ Sesiones en base de datos (compatible)
- ✅ Cache en base de datos (compatible)  
- ✅ Queue en base de datos (compatible)
- ✅ Storage local (compatible con Render)

### 3. Assets compilados
- [x] package.json tiene script `build`
- [x] Vite configurado correctamente
- [ ] **ACCIÓN**: Ejecutar `npm run build` localmente para verificar que compila

## 🚀 Pasos para desplegar

### Opción rápida (recomendada):
```bash
# Ejecutar el script que creé para ti:
deploy-render.bat
```

### Opción manual:
```bash
git add .
git commit -m "Add Render deployment configuration"
git push
```

## 🌐 En Render.com

1. [ ] Crear cuenta con GitHub
2. [ ] Elegir "Blueprint" 
3. [ ] Seleccionar repositorio hackathon-events
4. [ ] Aplicar configuración (render.yaml)
5. [ ] Esperar deploy (5-10 min)
6. [ ] Copiar URL generada
7. [ ] Agregar APP_URL en variables de entorno
8. [ ] Probar la aplicación

## ⚠️ Problemas comunes y soluciones

### Si el deploy falla:

1. **Revisa los logs** en Render
2. Busca líneas con "ERROR" o "FAILED"
3. Los errores más comunes son:
   - Falta APP_KEY (solución: generar en Environment)
   - Error en migraciones (solución: verificar compatibilidad PostgreSQL)
   - Assets no compilan (solución: verificar package.json)

### Si la app carga pero hay errores 500:

1. Ve a **Logs** en Render
2. Activa **Live Tail** para ver errores en tiempo real
3. Prueba diferentes páginas para identificar qué falla

## 🎯 Después del deploy exitoso

- [ ] Verificar que puedes acceder a la URL
- [ ] Probar registro de usuario
- [ ] Probar login
- [ ] Verificar que las rutas funcionan
- [ ] Probar subida de archivos (si aplica)

## 💡 Tips importantes

1. **Primera carga lenta**: El servicio gratuito "duerme" después de 15 min sin uso
   - Primera request: 30-50 segundos
   - Después: normal
   
2. **Mantener activo**: Usa UptimeRobot para hacer ping cada 14 minutos

3. **Base de datos dura 90 días**: 
   - Después necesitas pagar $7/mes
   - O migrar a Neon/Supabase (gratis permanente)

4. **Logs son tu amigo**: Siempre revisa logs si algo falla

## 📚 Documentación útil

- Render Docs: https://render.com/docs
- Laravel Deployment: https://laravel.com/docs/deployment
- PostgreSQL vs SQLite: https://www.postgresql.org/docs/

---

## ¿Todo listo?

Si marcaste todos los checkboxes arriba, ¡estás listo para desplegar!

**Ejecuta**: `deploy-render.bat` o sube manualmente los cambios a GitHub

**Luego sigue**: `GUIA_RENDER.md` para completar el proceso en Render.com
