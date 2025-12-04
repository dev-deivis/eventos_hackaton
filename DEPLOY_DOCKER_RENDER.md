# 🚨 DEPLOY URGENTE: Render + Supabase (CON DOCKER)

## ⏱️ TIEMPO ESTIMADO: 30 MINUTOS

---

## ✅ PASO 1: SUPABASE (5 minutos)

### 1.1 Crear Proyecto
1. Ve a https://supabase.com
2. Sign up / Login con GitHub
3. Click "New Project"
4. Configura:
   - **Organization:** (crea una si no tienes)
   - **Name:** `hackathon-events`
   - **Database Password:** Crea una fuerte (GUÁRDALA)
     Ejemplo: `HackEv2024!Secure`
   - **Region:** South America (São Paulo)
   - **Plan:** Free

5. Click "Create new project" (tarda ~2 minutos)

### 1.2 Obtener Credenciales

**IMPORTANTE: Anota estas credenciales AHORA**

```
Password: [la que pusiste arriba]
```

Cuando termine de crear:
1. Ve a **Settings** (⚙️) > **Database**
2. Busca la sección "Connection string"
3. Selecciona "URI" y copia todo
4. Debería verse así:
   ```
   postgresql://postgres.xxxxx:[YOUR-PASSWORD]@aws-0-sa-east-1.pooler.supabase.com:5432/postgres
   ```

5. Extrae:
   ```
   Host: aws-0-sa-east-1.pooler.supabase.com
        (o db.xxxxxxxxxxxxx.supabase.co)
   Port: 5432
   Database: postgres
   Username: postgres
   Password: [tu password]
   ```

---

## ✅ PASO 2: PREPARAR CÓDIGO (3 minutos)

### 2.1 Verificar Archivos

Ya tienes todo listo:
- ✅ `Dockerfile` (actualizado con seeders)
- ✅ `render.yaml` (configurado para Docker)
- ✅ `.dockerignore` (si no existe, créalo)

### 2.2 Crear .dockerignore (si no existe)

Ejecuta:
```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"
```

Crea archivo `.dockerignore` con:
```
.git
.env
.env.*
node_modules
vendor
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
bootstrap/cache/*
.idea
.vscode
*.log
.DS_Store
```

### 2.3 Subir a GitHub

```bash
git add .
git commit -m "Configurado para deploy con Docker y Supabase"
git push origin main
```

**Si NO tienes repositorio en GitHub:**
1. Ve a https://github.com/new
2. Crea repositorio: `hackathon-events`
3. NO inicialices con README
4. Ejecuta:
```bash
git init
git add .
git commit -m "Proyecto completo"
git branch -M main
git remote add origin https://github.com/TU-USUARIO/hackathon-events.git
git push -u origin main
```

---

## ✅ PASO 3: DEPLOY EN RENDER (15 minutos)

### 3.1 Crear Web Service

1. Ve a https://render.com
2. **Sign up / Login** con GitHub
3. Click **"New"** > **"Web Service"**
4. Click **"Connect a repository"**
5. Busca y selecciona: `hackathon-events`
6. Si no aparece:
   - Click "Configure account"
   - Da acceso al repositorio

### 3.2 Configuración del Servicio

**Información Básica:**
- **Name:** `hackathon-events`
- **Region:** Oregon (USA West) o el más cercano
- **Branch:** `main`
- **Root Directory:** (dejar vacío)

**Runtime:**
- **Environment:** `Docker` ✅

**Build & Deploy:**
- **Dockerfile Path:** `./Dockerfile` (ya lo detecta automático)
- **Docker Context:** `.` (ya lo detecta automático)

**Instance Type:**
- **Plan:** `Free`

### 3.3 Variables de Entorno (CRÍTICO)

Scroll hasta "Environment Variables" y agrega:

```env
APP_NAME=HackathonEvents
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:HpZthSqrslfh9UeEM0tc3jO/KYGOeCEMKKg2sti5ljA=
APP_URL=https://hackathon-events.onrender.com

# SUPABASE - USA TUS CREDENCIALES
DB_CONNECTION=pgsql
DB_HOST=aws-0-sa-east-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=TU_PASSWORD_DE_SUPABASE
DB_SSLMODE=require

# Laravel
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=debug
```

**IMPORTANTE:** 
- Reemplaza `DB_HOST` con tu host de Supabase
- Reemplaza `DB_PASSWORD` con tu password de Supabase
- Reemplaza `APP_URL` con la URL que te dé Render (puedes actualizarla después)

### 3.4 Crear y Desplegar

1. Click **"Create Web Service"**
2. Render empezará a construir (10-15 minutos)
3. Verás logs en tiempo real

---

## ✅ PASO 4: MONITOREAR DEPLOY (durante los 15 min)

### 4.1 Ver Logs

Los logs mostrarán:
```
Building...
=> [1/10] FROM php:8.2-cli
=> [2/10] RUN apt-get update...
=> [3/10] RUN docker-php-ext-install...
...
=> Building complete
Deploying...
Running: php artisan migrate --force
✓ Migration table created successfully
✓ Running migrations...
Running: php artisan db:seed --force
✓ Database seeding completed
Starting server...
✓ Server running on port 8080
```

### 4.2 Errores Comunes y Soluciones

**Error: "could not connect to server"**
- ✅ Verifica `DB_HOST` en variables de entorno
- ✅ Verifica `DB_PASSWORD`
- ✅ Asegúrate que `DB_SSLMODE=require`

**Error: "npm run build failed"**
- ✅ Ignorar, el timeout lo maneja
- ✅ Si persiste, comenta línea en Dockerfile:
  ```dockerfile
  # RUN timeout 300 npm run build || echo "Build completed or timed out"
  ```

**Error: "Class DatabaseSeeder not found"**
- ✅ El Dockerfile ya tiene `composer dump-autoload`
- ✅ Si persiste, usa Render Shell:
  ```bash
  composer dump-autoload
  php artisan db:seed --force
  ```

---

## ✅ PASO 5: VERIFICACIÓN (5 minutos)

### 5.1 Obtener URL

Cuando termine el deploy:
1. Render te dará una URL: `https://hackathon-events-xxxx.onrender.com`
2. Copia esa URL

### 5.2 Actualizar APP_URL

1. Render Dashboard > tu servicio > Environment
2. Busca `APP_URL`
3. Actualiza con tu URL real
4. Click "Save Changes" (re-desplegará, tarda 2 min)

### 5.3 Probar la Aplicación

Abre tu URL y prueba:

**Login Admin:**
```
Email: admin@hackathon.com
Password: password
```

**Login Juez:**
```
Email: juez1@hackathon.com
Password: password
```

**Login Participante:**
```
Email: juan.perez@alumno.com
Password: password
```

### 5.4 Verificar en Supabase

1. Supabase Dashboard > **Table Editor**
2. Deberías ver todas las tablas:
   - users (con 10+ usuarios)
   - roles (con 3 roles)
   - eventos
   - equipos
   - participantes
   - etc.

---

## 🚨 SOLUCIÓN RÁPIDA DE PROBLEMAS

### La app no carga

```bash
# En Render Dashboard > Shell
php artisan config:clear
php artisan cache:clear
```

### No hay datos en Supabase

```bash
# En Render Dashboard > Shell
php artisan migrate:fresh --force
php artisan db:seed --force
```

### Error 500

```bash
# Ver logs detallados
Render Dashboard > tu-servicio > Logs
```

Normalmente es:
- ✅ APP_KEY no configurada
- ✅ DB credentials incorrectas

---

## 📋 CHECKLIST FINAL

- [ ] Proyecto creado en Supabase
- [ ] Credenciales anotadas en papel/nota
- [ ] Código subido a GitHub
- [ ] Web Service creado en Render
- [ ] Variables de entorno configuradas
- [ ] Deploy completado (15 min)
- [ ] URL funcionando
- [ ] Login admin funciona
- [ ] Login juez funciona
- [ ] Login participante funciona
- [ ] Datos visibles en Supabase Table Editor

---

## 🎯 PARA MAÑANA

### URLs Importantes:
```
App: https://tu-app.onrender.com
Supabase: https://supabase.com/dashboard
Render: https://dashboard.render.com
```

### Credenciales Demo:
```
Admin:        admin@hackathon.com / password
Juez:         juez1@hackathon.com / password
Participante: juan.perez@alumno.com / password
```

### Si algo falla durante la presentación:

1. **Render Dashboard > Logs** - Ver qué pasó
2. **Render Dashboard > Manual Deploy** - Re-desplegar
3. **Supabase Dashboard > SQL Editor** - Ejecutar queries manuales

---

## ⏰ RESUMEN DE TIEMPOS

- ✅ Supabase: 5 min
- ✅ Preparar código: 3 min
- ✅ Configurar Render: 5 min
- ⏳ Deploy (espera): 15 min
- ✅ Verificación: 5 min

**TOTAL: ~33 minutos**

---

## 🆘 AYUDA URGENTE

Si algo no funciona:
1. Lee los logs en Render
2. Verifica variables de entorno
3. Verifica conexión en Supabase (Table Editor)
4. USA RENDER SHELL para comandos manuales

---

**¡ÉXITO EN TU DEPLOY! 🚀**

*Siguiente: Lee TARJETA_PRESENTACION.md para preparar tu demo*
