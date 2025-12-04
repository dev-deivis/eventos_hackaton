# 🚨 DEPLOY URGENTE: Render + Supabase

## ⏱️ TIEMPO ESTIMADO: 30 MINUTOS

---

## ✅ PASO 1: SUPABASE (5 minutos)

### 1.1 Crear Proyecto
1. Ve a https://supabase.com
2. Sign up / Login
3. Click "New Project"
4. Configura:
   - **Name:** `hackathon-events`
   - **Database Password:** Crea una contraseña fuerte (GUÁRDALA)
   - **Region:** South America (São Paulo) - el más cercano
   - **Plan:** Free

5. Click "Create new project" (tarda 2 minutos)

### 1.2 Obtener Credenciales

Mientras se crea, anota:
- **Password:** (la que pusiste)

Una vez creado:
1. Ve a Settings (⚙️) > Database
2. Busca "Connection string" > URI
3. Anota:
   - **Host:** `db.xxxxxxxxxxxxx.supabase.co`
   - **Database:** `postgres`
   - **Username:** `postgres`
   - **Password:** (la que pusiste)
   - **Port:** `5432`

---

## ✅ PASO 2: PREPARAR CÓDIGO (5 minutos)

### 2.1 Crear/Verificar Procfile

Ejecuta esto en tu terminal:

```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"
```

### 2.2 Verificar que render-build.sh tenga permisos

Ya está listo ✅ (incluye migraciones y seeders)

### 2.3 Subir Cambios a GitHub

```bash
git add .
git commit -m "Configurado para Supabase"
git push origin main
```

Si no tienes repositorio:
```bash
git init
git add .
git commit -m "Proyecto completo con Supabase"
git branch -M main
git remote add origin https://github.com/TU-USUARIO/hackathon-events.git
git push -u origin main
```

---

## ✅ PASO 3: DEPLOY EN RENDER (15 minutos)

### 3.1 Crear Cuenta y Proyecto

1. Ve a https://render.com
2. Sign up / Login con GitHub
3. Click "New" > "Web Service"
4. Conecta tu repositorio `hackathon-events`

### 3.2 Configuración Básica

- **Name:** `hackathon-events`
- **Region:** Oregon (USA West)
- **Branch:** `main`
- **Root Directory:** (dejar vacío)
- **Environment:** `PHP`
- **Build Command:** `./render-build.sh`
- **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
- **Plan:** Free

Click "Advanced" y configura:

### 3.3 Variables de Entorno (IMPORTANTE)

Agrega estas variables EN RENDER (NO en el código):

```
APP_NAME=HackathonEvents
APP_ENV=production
APP_DEBUG=false
APP_KEY=                           # Render lo genera automáticamente
APP_URL=https://TU-APP.onrender.com  # Render te dará esta URL

# SUPABASE - USA TUS CREDENCIALES
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxxx.supabase.co  # TU HOST DE SUPABASE
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_password_de_supabase   # TU PASSWORD DE SUPABASE
DB_SSLMODE=require

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stderr
LOG_LEVEL=debug
```

### 3.4 Deploy

1. Click "Create Web Service"
2. Render empezará a construir (tarda 5-10 minutos)
3. Verás los logs en tiempo real

---

## ✅ PASO 4: VERIFICACIÓN (5 minutos)

### 4.1 Verificar Logs de Build

Busca en los logs de Render:
```
Running migrations...
✓ 2024_01_01_000000_create_users_table
✓ 2024_01_01_000002_create_roles_table
...
Running seeders...
✓ DatabaseSeeder
```

### 4.2 Verificar en Supabase

1. Supabase Dashboard > Table Editor
2. Deberías ver todas tus tablas:
   - users
   - roles
   - eventos
   - equipos
   - participantes
   - etc.

3. Verificar datos de seeders:
   - Click en tabla `users` - deberías ver usuarios
   - Click en tabla `roles` - deberías ver roles

### 4.3 Probar la Aplicación

1. Render te dará una URL: `https://hackathon-events-XXXX.onrender.com`
2. Abre esa URL
3. Prueba:
   - ✅ Registro de usuario
   - ✅ Login
   - ✅ Ver eventos
   - ✅ Crear equipo
   - ✅ Dashboard admin

---

## 🚨 SOLUCIÓN DE PROBLEMAS URGENTE

### Error: "could not connect to server"

✅ **Causa:** Credenciales de Supabase incorrectas

**Solución:**
1. Render Dashboard > tu servicio > Environment
2. Verifica que `DB_HOST` tenga tu host de Supabase completo
3. Verifica que `DB_PASSWORD` sea correcto
4. Debe tener `DB_SSLMODE=require`
5. Click "Save Changes" y Render re-desplegará

### Error: "Class DatabaseSeeder not found"

✅ **Solución:**
En Render Dashboard > Shell:
```bash
composer dump-autoload
php artisan db:seed --force
```

### Build falla: "npm: command not found"

✅ **Solución:**
En `render-build.sh`, comenta las líneas de npm:
```bash
# npm ci --prefer-offline --no-audit
# npm run build
```

### La app muestra error 500

✅ **Solución:**
1. Render Dashboard > tu servicio > Logs
2. Busca el error específico
3. Usualmente es APP_KEY no generada:
   - Environment > APP_KEY > Click "Generate"

---

## 📋 CHECKLIST RÁPIDO

- [ ] Proyecto creado en Supabase
- [ ] Credenciales de Supabase anotadas
- [ ] Código actualizado con render.yaml
- [ ] Cambios subidos a GitHub
- [ ] Web Service creado en Render
- [ ] Variables de entorno configuradas en Render
- [ ] Build completado sin errores
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados
- [ ] Aplicación accesible
- [ ] Login funciona
- [ ] Datos visibles en Supabase

---

## 🎯 PARA TU EVALUACIÓN MAÑANA

### Preparar 3 Cuentas:

**1. Administrador (Profesor):**
- Email: `admin@hackathon.com`
- Password: (la que configures en seeder)

**2. Juez (Profesor/Evaluador):**
- Email: `juez@hackathon.com`  
- Password: (la que configures en seeder)

**3. Participante (Tú):**
- Email: `participante@hackathon.com`
- Password: (la que configures en seeder)

### Verificar Antes de la Presentación:

```bash
# Conectar a Supabase y verificar usuarios
php artisan tinker
User::all()->pluck('email', 'id');
Rol::all()->pluck('nombre', 'id');
```

### Durante la Demo:

1. **Como Admin:**
   - Crear evento
   - Asignar jueces
   - Ver dashboard completo

2. **Como Juez:**
   - Ver equipos asignados
   - Evaluar proyectos
   - Ver rankings

3. **Como Participante:**
   - Inscribirse a evento
   - Crear/unirse a equipo
   - Subir proyecto
   - Ver evaluaciones

---

## ⏰ TIEMPO TOTAL: ~30 MINUTOS

- Supabase: 5 min
- Preparar código: 5 min
- Deploy Render: 15 min (mayoría es espera)
- Verificación: 5 min

---

## 🆘 CONTACTO DE EMERGENCIA

Si tienes problemas durante el deploy:

1. **Logs de Render:** Dashboard > tu servicio > Logs
2. **Logs de Supabase:** Dashboard > Logs
3. **Shell de Render:** Dashboard > Shell (para comandos manuales)

---

## 📝 COMANDOS ÚTILES EN RENDER SHELL

Si necesitas ejecutar algo manualmente:

```bash
# Ver usuarios
php artisan tinker --execute="User::all();"

# Re-ejecutar migraciones
php artisan migrate:fresh --force

# Re-ejecutar seeders
php artisan db:seed --force

# Limpiar cache
php artisan config:clear
php artisan cache:clear

# Ver configuración de BD
php artisan tinker --execute="echo DB::connection()->getDatabaseName();"
```

---

¡ÉXITO EN TU PRESENTACIÓN! 🚀
