# 🚀 INICIO RÁPIDO: Migración a Supabase

## ⚡ 3 Pasos Simples

### **1. Configura Supabase (5 minutos)**
1. Crea cuenta en [supabase.com](https://supabase.com)
2. Crea nuevo proyecto
3. Copia las credenciales de conexión:
   - Settings > Database > Connection string

### **2. Edita `.env.supabase` (2 minutos)**
```env
DB_HOST=db.xxxxxxxxxxxxx.supabase.co  # Tu host
DB_PASSWORD=tu_password_aqui           # Tu contraseña
```

### **3. Ejecuta el Script (3 minutos)**
```bash
migrate-to-supabase.bat
```

## ✅ ¡Listo!

Tu aplicación ahora usa Supabase PostgreSQL.

---

## 📋 Comandos Útiles

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ver tablas en Supabase
php artisan tinker --execute="DB::select('SELECT tablename FROM pg_tables WHERE schemaname = \'public\'')"

# Volver a MySQL local
copy .env.mysql.backup .env
php artisan config:clear
```

---

## 🆘 Problemas Comunes

### No conecta a Supabase
✅ Verifica: `DB_SSLMODE=require` en `.env`
✅ Copia bien el host (incluye `.supabase.co`)

### Error en migraciones
✅ Ejecuta: `php artisan migrate:fresh --force`

### Error en seeders
✅ Ejecuta: `composer dump-autoload`

---

## 📖 Documentación Completa
Lee `GUIA_MIGRACION_SUPABASE.md` para más detalles.

---

**Tu base MySQL local sigue intacta** ✅  
**Puedes cambiar entre bases cuando quieras** ✅  
**Supabase es gratis para desarrollo** ✅
