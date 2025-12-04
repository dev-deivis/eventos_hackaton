# 🚀 Guía Completa: Migración a Supabase PostgreSQL

## 📋 **Tabla de Contenidos**
1. [Preparación](#preparación)
2. [Configurar Supabase](#configurar-supabase)
3. [Ejecutar Migración](#ejecutar-migración)
4. [Verificación](#verificación)
5. [Solución de Problemas](#solución-de-problemas)

---

## 🛠️ **Preparación**

### **Requisitos Previos:**
- ✅ Cuenta en [Supabase](https://supabase.com) (gratis)
- ✅ Proyecto Laravel funcionando localmente
- ✅ Migraciones y seeders listos

### **¿Qué hace la migración?**
- ✅ Conecta Laravel con PostgreSQL de Supabase
- ✅ Ejecuta todas tus migraciones
- ✅ Carga datos iniciales con los seeders
- ✅ Mantiene tu base MySQL local intacta

---

## 📦 **Paso 1: Configurar Supabase**

### **1.1 Crear Proyecto en Supabase**

1. Ve a [https://supabase.com](https://supabase.com)
2. Click en "Start your project"
3. Crea una nueva organización (si no tienes una)
4. Click en "New Project"
5. Configura:
   - **Name:** hackathon-events
   - **Database Password:** (guarda esta contraseña)
   - **Region:** Elige el más cercano (ej: South America)
   - **Pricing Plan:** Free (suficiente para desarrollo)

6. Click en "Create new project" (tarda ~2 minutos)

### **1.2 Obtener Credenciales de Conexión**

1. En tu proyecto de Supabase, ve a: **Settings** (⚙️) > **Database**
2. Busca la sección "Connection string"
3. Copia el **URI** (debería verse así):
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxxxxxxxxxx.supabase.co:5432/postgres
   ```

4. **Extrae los datos:**
   ```
   Host: db.xxxxxxxxxxxxx.supabase.co
   Port: 5432
   Database: postgres
   Username: postgres
   Password: [la que pusiste al crear el proyecto]
   ```

### **1.3 Editar `.env.supabase`**

Abre el archivo `.env.supabase` que se creó y reemplaza estas líneas:

```env
# REEMPLAZA ESTOS VALORES ⬇️
DB_HOST=db.xxxxxxxxxxxxx.supabase.co  # Tu host de Supabase
DB_PASSWORD=tu_password_aqui           # Tu contraseña
```

**Ejemplo completo:**
```env
DB_CONNECTION=pgsql
DB_HOST=db.abcdefghijklmno.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=MiSuperPassword123
DB_SSLMODE=require
```

---

## 🚀 **Paso 2: Ejecutar Migración**

### **Opción A: Script Automático (Recomendado)**

En Windows, simplemente ejecuta:
```bash
migrate-to-supabase.bat
```

El script:
1. ✅ Respaldará tu `.env` actual como `.env.mysql.backup`
2. ✅ Aplicará la configuración de Supabase
3. ✅ Verificará la conexión
4. ✅ Ejecutará migraciones
5. ✅ Cargará datos con seeders

### **Opción B: Manual**

Si prefieres hacerlo paso a paso:

```bash
# 1. Respaldar configuración actual
copy .env .env.mysql.backup

# 2. Aplicar configuración de Supabase
copy .env.supabase .env

# 3. Limpiar cache
php artisan config:clear
php artisan cache:clear

# 4. Verificar conexión
php artisan tinker --execute="DB::connection()->getPdo();"

# 5. Ejecutar migraciones (CUIDADO: borra datos en Supabase)
php artisan migrate:fresh --force

# 6. Cargar datos iniciales
php artisan db:seed --force
```

---

## ✅ **Paso 3: Verificación**

### **3.1 Verificar Migraciones**

```bash
php artisan migrate:status
```

Deberías ver algo como:
```
Migration name .......................... Batch / Status  
0001_01_01_000000_create_users_table ... [1] Ran  
0001_01_01_000001_create_cache_table ... [1] Ran  
... (todas las migraciones)
```

### **3.2 Verificar en Supabase Dashboard**

1. Ve a tu proyecto en Supabase
2. Click en **Table Editor** (📊)
3. Deberías ver todas tus tablas:
   - users
   - eventos
   - equipos
   - participantes
   - etc.

### **3.3 Verificar Datos de Seeders**

```bash
php artisan tinker --execute="echo 'Usuarios: ' . App\Models\User::count();"
php artisan tinker --execute="echo 'Roles: ' . App\Models\Rol::count();"
```

---

## 🔧 **Solución de Problemas**

### **❌ Error: "could not connect to server"**

**Causa:** Configuración incorrecta en `.env`

**Solución:**
1. Verifica que copiaste bien el host de Supabase
2. Asegúrate de incluir `DB_SSLMODE=require`
3. Verifica que la contraseña no tenga espacios

```env
# Correcto ✅
DB_SSLMODE=require
DB_HOST=db.abcdefghijklmno.supabase.co

# Incorrecto ❌
DB_SSLMODE=disable
DB_HOST=127.0.0.1
```

### **❌ Error: "SQLSTATE[08006]"**

**Causa:** Firewall o SSL mal configurado

**Solución:**
```env
DB_SSLMODE=require  # Debe estar en "require"
```

### **❌ Error: "syntax error at or near 'enum'"**

**Causa:** PostgreSQL no soporta ENUM de la misma forma que MySQL

**Solución:** Ya está contemplado. Laravel convierte automáticamente `enum()` a tipos compatibles en PostgreSQL.

### **❌ Error: "SQLSTATE[42P01]: Undefined table"**

**Causa:** Migraciones no se ejecutaron

**Solución:**
```bash
php artisan migrate:fresh --force
```

### **❌ Error: "Class 'DatabaseSeeder' not found"**

**Causa:** Autoload no actualizado

**Solución:**
```bash
composer dump-autoload
php artisan db:seed --force
```

---

## 🔄 **Volver a MySQL Local**

Si necesitas volver a tu base de datos MySQL local:

```bash
# Restaurar configuración MySQL
copy .env.mysql.backup .env

# Limpiar cache
php artisan config:clear
php artisan cache:clear

# Verificar conexión
php artisan migrate:status
```

---

## 📊 **Comparación: MySQL vs PostgreSQL**

### **Diferencias Automáticamente Manejadas por Laravel:**

| Característica | MySQL | PostgreSQL | Laravel |
|---------------|--------|------------|---------|
| ENUM | `ENUM('a','b')` | VARCHAR con CHECK | ✅ Auto |
| Auto-increment | AUTO_INCREMENT | SERIAL | ✅ Auto |
| Boolean | TINYINT(1) | BOOLEAN | ✅ Auto |
| DateTime | DATETIME | TIMESTAMP | ✅ Auto |

✅ **No necesitas modificar tus migraciones**, Laravel se encarga de la compatibilidad.

---

## 🎯 **Ventajas de Usar Supabase**

### **✅ Beneficios:**
1. **Gratis para desarrollo** - 500MB de base de datos
2. **Backups automáticos** - Punto de restauración diario
3. **APIs automáticas** - RESTful y GraphQL generadas
4. **Real-time** - Suscripciones a cambios en BD
5. **Dashboard visual** - Edita datos sin SQL
6. **Autenticación incluida** - Auth de usuarios
7. **Storage incluido** - Para imágenes/archivos
8. **Edge Functions** - Serverless functions

### **📦 Plan Gratuito Incluye:**
- 500 MB de espacio
- 1 GB de transferencia
- 2 GB de storage
- 50,000 usuarios activos mensuales
- Perfecto para desarrollo y MVPs

---

## 🌐 **Configuración para Producción**

### **Variables de Entorno Adicionales:**

```env
# .env para producción
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Supabase PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=${SUPABASE_PASSWORD}
DB_SSLMODE=require

# Optimizaciones
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

# Supabase con Storage
SUPABASE_URL=https://xxxxxxxxxxxxx.supabase.co
SUPABASE_KEY=tu_anon_key_aqui

# Session segura
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

---

## 🔐 **Seguridad**

### **Mejores Prácticas:**

1. **Nunca subas `.env` a Git**
   ```bash
   # Verifica que esté en .gitignore
   echo .env >> .gitignore
   ```

2. **Usa variables de entorno en producción**
   - Railway, Render, Vercel, etc. tienen variables de entorno
   - No hardcodees credenciales

3. **Habilita Row Level Security (RLS) en Supabase**
   ```sql
   -- En Supabase SQL Editor
   ALTER TABLE users ENABLE ROW LEVEL SECURITY;
   ```

4. **Cambia la contraseña después del desarrollo**
   - Settings > Database > Database Settings > Reset Database Password

---

## 📝 **Checklist de Migración**

Usa este checklist para asegurarte de que todo esté listo:

- [ ] Crear proyecto en Supabase
- [ ] Copiar credenciales a `.env.supabase`
- [ ] Respaldar `.env` actual
- [ ] Ejecutar `migrate-to-supabase.bat`
- [ ] Verificar migraciones con `php artisan migrate:status`
- [ ] Verificar datos en Supabase Dashboard
- [ ] Probar aplicación localmente con Supabase
- [ ] Actualizar `.env.production` con credenciales
- [ ] Configurar variables de entorno en hosting
- [ ] Habilitar SSL (`DB_SSLMODE=require`)
- [ ] Configurar backups automáticos en Supabase

---

## 🆘 **Soporte**

### **Recursos Útiles:**
- 📚 [Documentación Supabase](https://supabase.com/docs)
- 📚 [Laravel Database](https://laravel.com/docs/database)
- 💬 [Supabase Discord](https://discord.supabase.com/)
- 📧 [Soporte Supabase](https://supabase.com/support)

### **Logs y Debug:**

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ver queries SQL (en .env)
DB_LOG_QUERIES=true
LOG_LEVEL=debug
```

---

## ✨ **Próximos Pasos**

Después de migrar exitosamente:

1. **Configurar Supabase Storage** para imágenes
   ```env
   FILESYSTEM_DISK=supabase
   ```

2. **Habilitar Real-time** para notificaciones live

3. **Usar Supabase Auth** (opcional, alternativa a Laravel Breeze)

4. **Configurar Backups automáticos** en Supabase Dashboard

5. **Deploy a producción** (Railway, Render, Vercel)

---

## 🎉 **¡Listo!**

Tu aplicación ahora usa **Supabase PostgreSQL** como base de datos.

**Recuerda:**
- Tu base MySQL local sigue funcionando
- Puedes cambiar entre bases con solo cambiar el `.env`
- Supabase tiene un excelente panel visual para explorar datos

---

**Autor:** Sistema de Migración Automatizado  
**Fecha:** Diciembre 2024  
**Versión:** 1.0
