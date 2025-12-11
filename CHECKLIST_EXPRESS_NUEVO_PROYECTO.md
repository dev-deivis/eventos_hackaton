# ⚡ Checklist Express - Deploy Railway + Supabase

## 🎯 Configuración Probada (Hackathon Events)
✅ Railway: https://web-production-ef44a.up.railway.app  
✅ Supabase: aws-1-us-east-2.pooler.supabase.com  
✅ PHP: 8.3 | Laravel 11

---

## 📋 Checklist de 30 Minutos

### ☐ **1. Archivos Base (5 min)**

```bash
# Copiar del proyecto exitoso a tu nuevo proyecto:
nixpacks.toml
Procfile
.env.supabase (ajustar valores)
```

### ☐ **2. Supabase (5 min)**

```
1. supabase.com → New Project
2. Guardar password
3. Settings > Database > Connection Pooler
4. Copiar: Host, User, Password
```

### ☐ **3. Probar Local (10 min)**

```bash
copy .env .env.backup
copy .env.supabase .env
php artisan config:clear
php artisan migrate:fresh --seed
php artisan serve  # Verificar que funcione
```

### ☐ **4. GitHub (5 min)**

```bash
git init
git add .
git commit -m "Ready for Railway"
git push origin main
```

### ☐ **5. Railway Variables (5 min)**

```env
APP_KEY=[generar: php artisan key:generate --show]
APP_URL=[Railway lo da]
DB_HOST=[Supabase Pooler]
DB_USERNAME=postgres.xxxxxxxx
DB_PASSWORD=[Supabase]
DB_SSLMODE=require ⚠️
```

### ☐ **6. Verificar**

```
✅ URL accesible
✅ Login funciona
✅ Sin errores en Railway Logs
```

---

## 🔑 Variables Críticas (Copiar/Pegar)

```env
APP_DEBUG=false
APP_ENV=production
APP_KEY=
APP_NAME=
APP_URL=
ASSET_URL=
CACHE_DRIVER=array
CACHE_STORE=database
DB_CONNECTION=pgsql
DB_DATABASE=postgres
DB_HOST=
DB_PASSWORD=
DB_PORT=5432
DB_SSLMODE=require
DB_USERNAME=
DB_POOL_MIN=2
DB_POOL_MAX=10
FORCE_HTTPS=true
LOG_LEVEL=error
PHP_MEMORY_LIMIT=256M
PHP_OPCACHE_ENABLE=1
QUEUE_CONNECTION=database
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
VIEW_COMPILED_PATH=/tmp/views
```

---

## ⚠️ 3 Errores Que Matan el Deploy

```env
# 1. ❌ Sin SSL
DB_SSLMODE=require  # ⚠️ OBLIGATORIO

# 2. ❌ APP_KEY vacío
APP_KEY=base64:xxxxx  # Generar con artisan

# 3. ❌ Host incorrecto
DB_HOST=aws-1-us-east-2.pooler.supabase.com  # Usar POOLER
```

---

## 🚀 Script Rápido

```batch
@echo off
php artisan key:generate --show > app_key.txt
copy .env .env.backup
copy .env.supabase .env
php artisan config:clear
npm run build
git add .
echo Listo! Ahora: git commit + push + Railway
pause
```

---

## 📞 Si Falla

```bash
# Error conexión DB
→ Verificar DB_SSLMODE=require

# Error APP_KEY
→ php artisan key:generate --show

# Error tablas
→ railway shell
→ php artisan migrate --force
```

---

**Tiempo:** 30-45 min | **Dificultad:** Fácil con esta guía
