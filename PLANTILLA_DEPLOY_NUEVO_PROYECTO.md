# 🚀 Plantilla de Deploy Railway + Supabase (Basada en Proyecto Exitoso)

## 📋 Configuración que YA funciona

Esta plantilla está basada en el proyecto **Hackathon Events** que está exitosamente desplegado en:
- **URL:** https://web-production-ef44a.up.railway.app
- **Railway:** ✅ Funcionando
- **Supabase:** ✅ Conectado

---

## ⚡ Quick Start - Nuevo Proyecto

### **Paso 1: Preparar tu Proyecto Laravel** ⏱️ 10 min

```bash
# 1. Navegar a tu proyecto
cd C:\ruta\a\tu\nuevo-proyecto

# 2. Verificar que sea Laravel
php artisan --version

# 3. Crear archivos necesarios (si no existen)
# Los copiaremos del proyecto exitoso
```

---

### **Paso 2: Crear Archivos de Configuración** ⏱️ 5 min

#### **2.1 Crear `nixpacks.toml`** (Copia exacta del proyecto exitoso)

Crear archivo en la raíz: `nixpacks.toml`

```toml
[phases.setup]
nixPkgs = ['nodejs-18_x', 'php83', 'php83Packages.composer', 'php83Extensions.intl', 'php83Extensions.opcache', 'postgresql']

[phases.install]
cmds = [
    'composer install --no-dev --optimize-autoloader --no-scripts',
    'npm ci --include=dev',
]

[phases.build]
cmds = [
    'npm run build',
    'composer dump-autoload --optimize --classmap-authoritative',
]

[staticAssets]
'/app/public' = '/'

[start]
cmd = 'php -d opcache.enable=1 -d opcache.enable_cli=1 -d opcache.memory_consumption=128 -d opcache.interned_strings_buffer=8 -d opcache.max_accelerated_files=10000 -d opcache.validate_timestamps=0 artisan config:cache && php -d opcache.enable=1 -d opcache.enable_cli=1 artisan route:cache && php -d opcache.enable=1 -d opcache.enable_cli=1 -d opcache.memory_consumption=128 -d opcache.interned_strings_buffer=8 -d opcache.max_accelerated_files=10000 -d opcache.validate_timestamps=0 artisan serve --host=0.0.0.0 --port=$PORT'
```

#### **2.2 Crear `Procfile`** (Opcional, backup del nixpacks)

```procfile
web: php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT
worker: php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

#### **2.3 Crear `.env.supabase`** (Plantilla)

```env
APP_NAME="Tu Nombre de App"
APP_ENV=production
APP_KEY=base64:xxxxx  # Se genera después
APP_DEBUG=false
APP_URL=https://tu-app.up.railway.app  # Railway lo asigna

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_MX

BCRYPT_ROUNDS=10

LOG_CHANNEL=stack
LOG_LEVEL=error

# ============================================
# SUPABASE POSTGRESQL DATABASE
# ============================================
DB_CONNECTION=pgsql
DB_HOST=aws-1-us-east-2.pooler.supabase.com  # Cambiar por tu host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxx  # Cambiar por tu username
DB_PASSWORD=tu_password  # Cambiar por tu password
DB_SSLMODE=require
DB_POOL_MIN=2
DB_POOL_MAX=10

SESSION_DRIVER=cookie
SESSION_LIFETIME=120

CACHE_DRIVER=array
CACHE_STORE=database

QUEUE_CONNECTION=database

# Correos (Opcional - usa tus credenciales Brevo)
MAIL_ENABLED=false
MAIL_MAILER=log

VITE_APP_NAME="${APP_NAME}"

# Optimizaciones
FORCE_HTTPS=true
PHP_MEMORY_LIMIT=256M
PHP_OPCACHE_ENABLE=1
VIEW_COMPILED_PATH=/tmp/views
```

---

### **Paso 3: Crear Proyecto en Supabase** ⏱️ 5 min

1. **Ir a:** https://supabase.com/dashboard
2. **New project:**
   - Name: `tu-nuevo-proyecto`
   - Database Password: **(guardar esto!)**
   - Region: South America (o el más cercano)
3. **Esperar ~2 minutos** a que se cree

4. **Obtener credenciales:**
   - Settings > Database
   - Connection Pooler (importante: usar **Pooler**, no Direct)
   
   Copiar:
   ```
   Host: aws-1-us-east-2.pooler.supabase.com
   Port: 5432
   Database: postgres
   User: postgres.xxxxxxxx
   Password: [el que guardaste]
   ```

5. **Actualizar `.env.supabase`** con estos datos

---

### **Paso 4: Probar Localmente con Supabase** ⏱️ 10 min

```bash
# 1. Respaldar .env actual
copy .env .env.mysql.backup

# 2. Usar configuración Supabase
copy .env.supabase .env

# 3. Limpiar cache
php artisan config:clear
php artisan cache:clear

# 4. Verificar conexión
php artisan tinker --execute="echo 'DB: ' . DB::connection()->getDatabaseName();"
# Debe mostrar: DB: postgres

# 5. Ejecutar migraciones
php artisan migrate:fresh --seed

# 6. Probar app
php artisan serve
# Abrir: http://localhost:8000
# Verificar: Login, datos, funcionalidades
```

**✅ Si todo funciona localmente, continuar al siguiente paso**

---

### **Paso 5: Subir a GitHub** ⏱️ 5 min

```bash
# 1. Inicializar Git (si no lo has hecho)
git init

# 2. Agregar .gitignore
echo .env >> .gitignore
echo .env.* >> .gitignore
echo !.env.example >> .gitignore
echo node_modules >> .gitignore
echo /vendor >> .gitignore

# 3. Commit inicial
git add .
git commit -m "Initial commit - ready for Railway deploy"

# 4. Crear repositorio en GitHub
# Ir a: https://github.com/new
# Nombre: tu-nuevo-proyecto

# 5. Push
git branch -M main
git remote add origin https://github.com/TU-USUARIO/tu-nuevo-proyecto.git
git push -u origin main
```

---

### **Paso 6: Crear Proyecto en Railway** ⏱️ 3 min

1. **Ir a:** https://railway.app/dashboard
2. **New Project** > **Deploy from GitHub repo**
3. **Seleccionar:** tu-nuevo-proyecto
4. **Railway detectará Laravel automáticamente**

---

### **Paso 7: Configurar Variables en Railway** ⏱️ 5 min

Click en tu servicio > **Variables** > **Raw Editor**

Pegar estas variables (ajustar valores):

```env
APP_DEBUG=false
APP_ENV=production
APP_KEY=base64:xxxxxx
APP_NAME="Tu App Name"
APP_URL=https://web-production-xxxx.up.railway.app
ASSET_URL=https://web-production-xxxx.up.railway.app

BCRYPT_ROUNDS=10

CACHE_DRIVER=array
CACHE_STORE=database

DB_CONNECTION=pgsql
DB_DATABASE=postgres
DB_HOST=aws-1-us-east-2.pooler.supabase.com
DB_PASSWORD=tu_password_supabase
DB_POOL_MAX=10
DB_POOL_MIN=2
DB_PORT=5432
DB_SSLMODE=require
DB_USERNAME=postgres.xxxxxxxx

FORCE_HTTPS=true

LOG_CHANNEL=stack
LOG_LEVEL=error

QUEUE_CONNECTION=database

SESSION_DRIVER=cookie
SESSION_LIFETIME=120

PHP_MEMORY_LIMIT=256M
PHP_OPCACHE_ENABLE=1

VIEW_COMPILED_PATH=/tmp/views

VITE_APP_NAME=${APP_NAME}
```

**📝 Variables que DEBES cambiar:**
- `APP_KEY` (generar nuevo)
- `APP_NAME`
- `APP_URL` (Railway te da una URL)
- `ASSET_URL` (misma que APP_URL)
- `DB_HOST` (de tu Supabase)
- `DB_USERNAME` (de tu Supabase)
- `DB_PASSWORD` (de tu Supabase)

**🔑 Generar APP_KEY:**
```bash
# En local:
php artisan key:generate --show
# Copiar el output a Railway Variables
```

---

### **Paso 8: Si usas Correos (Brevo)** ⏱️ 5 min (Opcional)

Si tu app envía correos, agregar también:

```env
MAIL_ENABLED=true
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="Tu App Name"
MAIL_HOST=smtp-relay.brevo.com
MAIL_MAILER=smtp
MAIL_PASSWORD=xsmtpsib-xxxxxx
MAIL_PORT=587
MAIL_USERNAME=xxxxxx@smtp-brevo.com
```

**Obtener credenciales Brevo:**
1. https://app.brevo.com/
2. SMTP & API > API Keys
3. Create new API key
4. Copiar y pegar en `MAIL_PASSWORD`

---

### **Paso 9: Deploy y Verificación** ⏱️ 5 min

1. **Railway desplegará automáticamente** (~2-3 min)
2. **Ver progreso:** Deployments tab
3. **Ver logs:** Logs tab

**Buscar en logs:**
```
✅ "Starting application"
✅ "Running migrations"
✅ "Application ready"
```

4. **Obtener URL:** Settings > Domains
   - Ejemplo: `web-production-xxxx.up.railway.app`

5. **Abrir URL y probar:**
   - Login
   - Funcionalidades básicas
   - Ver datos en Supabase Dashboard

---

## 🔧 Script Automatizado (Opcional)

Puedes crear un archivo `setup-railway.bat` para automatizar:

```batch
@echo off
echo ============================================
echo   SETUP RAILWAY + SUPABASE
echo ============================================

REM Paso 1: Respaldar .env
echo [1/6] Respaldando .env...
copy .env .env.backup

REM Paso 2: Generar APP_KEY
echo [2/6] Generando APP_KEY...
php artisan key:generate --show > app_key.txt
echo APP_KEY generado y guardado en app_key.txt

REM Paso 3: Limpiar cache
echo [3/6] Limpiando cache...
php artisan config:clear
php artisan cache:clear

REM Paso 4: Compilar assets
echo [4/6] Compilando assets...
npm run build

REM Paso 5: Git
echo [5/6] Preparando Git...
git add .
git status

echo [6/6] Siguiente paso:
echo   1. Subir a GitHub: git commit -m "Ready for Railway" && git push
echo   2. Crear proyecto en Railway
echo   3. Copiar variables de .env.supabase + APP_KEY de app_key.txt
echo.
pause
```

---

## ✅ Checklist Final

### **Pre-Deploy:**
- [ ] Proyecto Laravel funcionando localmente
- [ ] `nixpacks.toml` creado
- [ ] `.env.supabase` configurado
- [ ] Conexión a Supabase probada localmente
- [ ] Migraciones ejecutadas en Supabase
- [ ] `npm run build` exitoso
- [ ] Código en GitHub

### **Railway:**
- [ ] Proyecto creado
- [ ] Variables configuradas
- [ ] `DB_SSLMODE=require` presente
- [ ] `APP_KEY` generado y copiado
- [ ] Deploy sin errores

### **Verificación:**
- [ ] URL accesible
- [ ] Login funciona
- [ ] Datos visibles
- [ ] Sin errores en logs
- [ ] Supabase muestra tablas

---

## 🐛 Troubleshooting Rápido

### **Error: "could not connect to server"**
```env
# Verificar:
DB_SSLMODE=require
DB_HOST=aws-1-us-east-2.pooler.supabase.com  # Usar POOLER
```

### **Error: "No encryption key"**
```bash
# Generar y copiar a Railway Variables
php artisan key:generate --show
```

### **Error: "Undefined table"**
```bash
# En Railway Shell
railway shell
php artisan migrate --force
```

### **Assets no cargan**
```bash
# Local: compilar
npm run build

# Verificar que nixpacks.toml tenga:
[phases.build]
cmds = ['npm run build']
```

---

## 📊 Comparación con Proyecto Exitoso

| Aspecto | Hackathon Events | Tu Nuevo Proyecto |
|---------|------------------|-------------------|
| **Framework** | Laravel 11 | Laravel (?) |
| **PHP** | 8.3 | 8.3 (mismo) |
| **Base Datos** | Supabase PostgreSQL | Supabase PostgreSQL |
| **Hosting** | Railway | Railway |
| **URL** | web-production-ef44a | web-production-xxxx |
| **Correos** | Brevo (SMTP) | (opcional) |

---

## 💡 Tips Basados en Proyecto Exitoso

### **1. Usar Connection Pooler**
```env
# ✅ BUENO - Pooler (soporta más conexiones)
DB_HOST=aws-1-us-east-2.pooler.supabase.com

# ❌ EVITAR - Direct (límite de conexiones)
DB_HOST=db.eispdmymfkisgwvydpxe.supabase.com
```

### **2. Session en Cookie (no en DB)**
```env
# ✅ Mejor performance
SESSION_DRIVER=cookie

# vs

# ⚠️ Más queries a DB
SESSION_DRIVER=database
```

### **3. Cache Array en Producción**
```env
# ✅ Railway tiene filesystem efímero
CACHE_DRIVER=array
CACHE_STORE=database
```

### **4. View Compiled Path en /tmp**
```env
# ✅ Railway recomienda /tmp para archivos temporales
VIEW_COMPILED_PATH=/tmp/views
```

### **5. Forzar HTTPS**
```env
# ✅ Asegura conexiones seguras
FORCE_HTTPS=true
```

---

## 🎯 Diferencias con el Proyecto Original

Si tu nuevo proyecto tiene características diferentes:

### **Sin Correos:**
```env
MAIL_ENABLED=false
MAIL_MAILER=log
```

### **Con Autenticación Social:**
```env
# Agregar:
GITHUB_CLIENT_ID=xxxxx
GITHUB_CLIENT_SECRET=xxxxx
GITHUB_REDIRECT=https://tu-app.railway.app/auth/github/callback
```

### **Con Storage S3/Supabase:**
```env
# Agregar:
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=xxxxx
AWS_SECRET_ACCESS_KEY=xxxxx
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=tu-bucket
```

---

## 📝 Template de Variables para Copiar

```env
APP_DEBUG=false
APP_ENV=production
APP_KEY=
APP_NAME=
APP_URL=
ASSET_URL=
BCRYPT_ROUNDS=10
CACHE_DRIVER=array
CACHE_STORE=database
DB_CONNECTION=pgsql
DB_DATABASE=postgres
DB_HOST=
DB_PASSWORD=
DB_POOL_MAX=10
DB_POOL_MIN=2
DB_PORT=5432
DB_SSLMODE=require
DB_USERNAME=
FORCE_HTTPS=true
LOG_CHANNEL=stack
LOG_LEVEL=error
PHP_MEMORY_LIMIT=256M
PHP_OPCACHE_ENABLE=1
QUEUE_CONNECTION=database
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
VIEW_COMPILED_PATH=/tmp/views
VITE_APP_NAME=${APP_NAME}
```

---

## ⏱️ Tiempo Estimado Total

| Fase | Tiempo |
|------|--------|
| Preparar archivos | 10 min |
| Crear Supabase | 5 min |
| Probar local | 10 min |
| Subir a GitHub | 5 min |
| Configurar Railway | 10 min |
| Deploy y verificar | 5 min |
| **TOTAL** | **45 min** |

---

**¿Listo para empezar?** Sigue esta plantilla paso a paso y tendrás tu nuevo proyecto desplegado en menos de una hora. 🚀
