# ✅ Checklist de Migración a Supabase

## 📦 **ANTES DE EMPEZAR**
- [ ] Tengo cuenta en Supabase
- [ ] Mi aplicación Laravel funciona localmente con MySQL
- [ ] Tengo migraciones y seeders listos
- [ ] He hecho backup de mi proyecto

---

## 🔧 **CONFIGURACIÓN SUPABASE**
- [ ] Crear proyecto en Supabase
- [ ] Anotar contraseña del proyecto
- [ ] Copiar string de conexión (Settings > Database)
- [ ] Extraer: Host, Port, Database, Username, Password

---

## 📝 **CONFIGURACIÓN LOCAL**
- [ ] Editar `.env.supabase` con mis credenciales
- [ ] Verificar que `DB_SSLMODE=require` esté presente
- [ ] Verificar que el host termine en `.supabase.co`
- [ ] No hay espacios en la contraseña

---

## 🚀 **EJECUTAR MIGRACIÓN**
- [ ] Ejecutar `migrate-to-supabase.bat`
- [ ] Confirmar cuando pregunte si continuar
- [ ] Esperar a que termine (puede tardar 2-5 minutos)
- [ ] Ver mensaje "MIGRACION COMPLETADA EXITOSAMENTE"

---

## ✅ **VERIFICACIÓN**
- [ ] Ejecutar `php artisan migrate:status` (todas "Ran")
- [ ] Abrir Supabase Dashboard > Table Editor
- [ ] Verificar que existen las tablas principales:
  - [ ] users
  - [ ] roles
  - [ ] eventos
  - [ ] equipos
  - [ ] participantes
  - [ ] proyectos
- [ ] Verificar datos de seeders:
  - [ ] Hay usuarios en tabla `users`
  - [ ] Hay roles en tabla `roles`
  - [ ] Hay carreras en tabla `carreras`

---

## 🧪 **PRUEBAS**
- [ ] Ejecutar `php artisan serve`
- [ ] Abrir http://localhost:8000
- [ ] Intentar login
- [ ] Navegar por la aplicación
- [ ] Crear un registro de prueba
- [ ] Verificar que aparece en Supabase Dashboard

---

## 📊 **PRODUCCIÓN** (Opcional)
- [ ] Copiar credenciales a `.env.production`
- [ ] Configurar variables de entorno en hosting
- [ ] Cambiar `APP_ENV=production`
- [ ] Cambiar `APP_DEBUG=false`
- [ ] Deploy a producción
- [ ] Verificar conexión a Supabase desde producción

---

## 🔒 **SEGURIDAD**
- [ ] `.env` está en `.gitignore`
- [ ] No compartir credenciales públicamente
- [ ] Habilitar Row Level Security en Supabase
- [ ] Usar HTTPS en producción
- [ ] Configurar backups en Supabase

---

## 🔄 **ROLLBACK** (Si algo sale mal)
- [ ] Ejecutar: `copy .env.mysql.backup .env`
- [ ] Ejecutar: `php artisan config:clear`
- [ ] Verificar: `php artisan migrate:status`
- [ ] Tu MySQL local debería funcionar nuevamente

---

## 📚 **RECURSOS**
- [ ] Leer `GUIA_MIGRACION_SUPABASE.md`
- [ ] Ver `INICIO_RAPIDO_SUPABASE.md`
- [ ] Guardar link del proyecto Supabase
- [ ] Anotar credenciales en lugar seguro

---

## ✨ **OPCIONAL - FUNCIONES AVANZADAS**
- [ ] Configurar Supabase Storage para archivos
- [ ] Habilitar Real-time subscriptions
- [ ] Explorar Supabase Auth
- [ ] Configurar Edge Functions
- [ ] Habilitar APIs automáticas (REST/GraphQL)

---

## 🎉 **¡COMPLETADO!**
Si marcaste todos los checks principales, ¡tu migración fue exitosa!

**Fecha de migración:** __________  
**Versión Laravel:** __________  
**Versión PostgreSQL:** __________  
**Plan Supabase:** Free / Pro  

**Notas adicionales:**
_________________________________
_________________________________
_________________________________
