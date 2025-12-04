# 🚀 Guía Completa: Desplegar Laravel en Render (GRATIS)

## ✅ Archivos creados para ti:
- ✓ `render-build.sh` - Script de construcción
- ✓ `render.yaml` - Configuración automática
- ✓ `Procfile` - Comando de inicio
- ✓ `.env.render` - Variables de entorno de ejemplo

---

## 📋 Paso 1: Subir cambios a GitHub

Abre una terminal en tu proyecto y ejecuta:

```bash
git add .
git commit -m "Configuración para Render"
git push
```

⚠️ **Importante**: Asegúrate de que tu repositorio esté en GitHub (no GitLab ni Bitbucket).

---

## 🌐 Paso 2: Crear cuenta en Render

1. Ve a: **https://render.com**
2. Click en **"Get Started"**
3. Selecciona **"Sign up with GitHub"**
4. Autoriza a Render para acceder a tus repositorios

---

## 🎯 Paso 3: Crear el servicio web

### Opción A: Usando Blueprint (Recomendado - Todo automático)

1. En el dashboard de Render, click en **"New +"**
2. Selecciona **"Blueprint"**
3. Conecta tu repositorio **hackathon-events**
4. Render detectará automáticamente el archivo `render.yaml`
5. Click en **"Apply"**
6. ¡Listo! Render creará:
   - El web service (tu aplicación)
   - La base de datos PostgreSQL
   - Todas las variables de entorno

### Opción B: Manual (Más control)

1. En el dashboard, click en **"New +"**
2. Selecciona **"Web Service"**
3. Conecta tu repositorio de GitHub
4. Configura:
   - **Name**: hackathon-events
   - **Runtime**: PHP
   - **Build Command**: `./render-build.sh`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Plan**: Free

---

## 💾 Paso 4: Crear base de datos PostgreSQL

### Si usaste Blueprint (Opción A):
✓ Ya está creada automáticamente, salta al Paso 5

### Si usaste Manual (Opción B):
1. Click en **"New +"** → **"PostgreSQL"**
2. Configura:
   - **Name**: hackathon-events-db
   - **Database**: hackathon_events
   - **User**: hackathon_user
   - **Plan**: Free
3. Click en **"Create Database"**
4. Espera 2-3 minutos a que se cree

---

## 🔗 Paso 5: Conectar base de datos a tu app

1. Ve a tu **Web Service** (hackathon-events)
2. Click en **"Environment"** en el menú izquierdo
3. Click en **"Add Environment Variable"**
4. Agrega una por una:

### Variables requeridas:

```
APP_NAME = Hackathon Events
APP_ENV = production
APP_DEBUG = false
APP_KEY = (Click en "Generate" para crear automáticamente)
LOG_CHANNEL = stderr
SESSION_DRIVER = database
QUEUE_CONNECTION = database
CACHE_STORE = database
DB_CONNECTION = pgsql
```

5. Para conectar la base de datos:
   - Click en **"Add from Database"**
   - Selecciona **hackathon-events-db**
   - Render agregará automáticamente: `DATABASE_URL`

---

## 🌍 Paso 6: Configurar APP_URL

1. Después del primer deploy, Render te dará una URL como:
   ```
   https://hackathon-events-xxxx.onrender.com
   ```

2. Copia esa URL
3. Ve a **Environment** nuevamente
4. Agrega:
   ```
   APP_URL = https://hackathon-events-xxxx.onrender.com
   ```
5. Click en **"Save Changes"**

---

## ⏱️ Paso 7: Esperar el deploy


El primer deploy tomará **5-10 minutos**. Verás el progreso en tiempo real:

```
==> Installing dependencies
==> Building assets
==> Running migrations
==> Deploy live
```

✅ Cuando veas **"Deploy live"**, tu app estará funcionando!

---

## 🎉 Paso 8: Verificar que funciona

1. Click en la URL que Render te dio
2. Deberías ver tu aplicación funcionando
3. Intenta registrarte/login para verificar que la BD funciona

---

## 🔧 Troubleshooting

### ❌ Error: "Permission denied" en render-build.sh

**Solución**: Dar permisos de ejecución al script localmente antes de subirlo:

```bash
git update-index --chmod=+x render-build.sh
git add render-build.sh
git commit -m "Fix permissions"
git push
```

Luego en Render → **Manual Deploy** → **Clear build cache & deploy**

---

### ❌ Error en migraciones

**Problema**: SQLite y PostgreSQL tienen diferencias.

**Soluciones comunes**:

1. **Booleanos**: En PostgreSQL, usar `boolean` no `tinyint`
2. **JSON**: PostgreSQL soporta JSON nativamente
3. **Text**: En PostgreSQL, `text` no tiene límite (no necesitas `longtext`)

**Cómo verificar tus migraciones**:

```bash
# Revisar localmente con PostgreSQL
php artisan migrate --database=pgsql
```

---

### ❌ Assets no se cargan (CSS/JS)

**Verificar**:
1. Que `npm run build` se ejecutó correctamente (ver logs)
2. Que `APP_URL` está configurado correctamente
3. Que `public/build` existe después del build

**Solución rápida**:
En Render → **Manual Deploy** → **Clear build cache & deploy**

---

### ❌ Error 500 después del deploy

**Pasos para debuggear**:

1. Ve a tu Web Service en Render
2. Click en **"Logs"** en el menú izquierdo
3. Busca el error específico
4. Errores comunes:
   - Falta `APP_KEY` → Generar en Environment
   - Error de permisos storage → Render lo maneja automáticamente
   - Error de BD → Verificar `DATABASE_URL` está conectada

---

## 🎁 Ventajas del plan gratuito

✅ **Incluye**:
- 750 horas/mes de servicio web
- PostgreSQL (1GB storage, 90 días)
- SSL/HTTPS automático
- Deploys automáticos desde GitHub
- Logs en tiempo real
- Dominio .onrender.com

⚠️ **Limitaciones**:
- El servicio "duerme" después de 15 minutos sin uso
- Primera request después de dormir toma 30-50 segundos
- Después funciona normal

---

## 🚀 Próximos pasos

### Dominio personalizado (Opcional)
1. Compra un dominio (Namecheap, GoDaddy, etc.)
2. En Render → **Settings** → **Custom Domain**
3. Agrega tu dominio
4. Configura los DNS según las instrucciones

### Mantener la app activa 24/7 (Opcional)
Usa un servicio de "ping" gratuito:
- **UptimeRobot** (https://uptimerobot.com)
- **Cron-job.org** (https://cron-job.org)

Configurar para hacer ping a tu URL cada 14 minutos.

### Actualizar después de 90 días
Cuando expire la BD gratuita:

**Opción 1**: Pagar $7/mes por PostgreSQL en Render

**Opción 2**: Usar BD externa gratuita:
- **Neon** (https://neon.tech) - Postgres gratis permanente
- **Supabase** (https://supabase.com) - Postgres + extras gratis

---

## 📞 Soporte

Si algo no funciona:
1. Revisa los **Logs** en Render
2. Verifica las variables de entorno
3. Intenta **Clear build cache & deploy**
4. Contacta soporte de Render (muy responsivos)

---

## ✨ ¡Todo listo!

Tu aplicación Laravel ahora está:
- ✅ Desplegada en Render
- ✅ Con base de datos PostgreSQL
- ✅ Con SSL/HTTPS automático
- ✅ Con deploys automáticos desde GitHub
- ✅ Completamente GRATIS

**URL de tu proyecto**: `https://hackathon-events-xxxx.onrender.com`

¡Comparte tu link y disfruta! 🎉
