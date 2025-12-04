# 🚀 DEPLOYMENT RAILWAY - BASE DE DATOS NUEVA

## ✅ LO QUE VAS A HACER

**NO hay migración de datos**. Simplemente:

1. ✅ Railway crea MySQL vacío
2. ✅ Ejecutas tus migraciones (crea tablas)
3. ✅ Ejecutas tus seeders (inserta datos)
4. ✅ ¡Listo!

Es como hacer `php artisan migrate:fresh --seed` pero en Railway.

---

## 🎯 PROCESO EXACTO

```
Railway MySQL (vacío)
        ↓
php artisan migrate --force
        ↓
Crea todas las tablas:
- users
- roles
- eventos
- equipos
- proyectos
- etc.
        ↓
php artisan db:seed --force
        ↓
Ejecuta tus seeders:
- RolSeeder → admin, juez, participante
- UserSeeder → usuarios de prueba
- CarreraSeeder → carreras
- EventoSeeder → eventos de ejemplo
- Todos los demás...
        ↓
✅ Base de datos lista con datos de prueba
```

**NO se copia nada de tu SQLite local.**
**Todo se crea desde cero en Railway.**

---

## 🚀 DEPLOYMENT EN 3 PASOS

### PASO 1: Subir a GitHub (2 min)

```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"

git add .
git commit -m "feat: preparar para Railway"
git push origin main
```

### PASO 2: Configurar Railway (5 min)

#### A) Crear Proyecto
1. Ve a https://railway.app
2. Click "New Project"
3. "Deploy from GitHub repo"
4. Selecciona `hackathon-events`

#### B) Agregar MySQL (NUEVA base de datos vacía)
1. En tu proyecto, click "+ New"
2. "Database"
3. "MySQL"
4. Railway crea MySQL vacío ✨

#### C) Configurar Variables
En tu servicio Laravel (no en MySQL), ve a "Variables" y agrega:

```env
APP_NAME=Hackathon Events
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-proyecto.up.railway.app
APP_LOCALE=es
APP_FALLBACK_LOCALE=es

DB_CONNECTION=mysql

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
LOG_LEVEL=error
```

**NOTA:** Las variables de MySQL (MYSQLHOST, MYSQLPORT, etc.) se configuran automáticamente desde el servicio MySQL.

#### D) Configurar Build
En "Settings" → "Builder":

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm ci && npm run build
```

**Start Command:**
```bash
php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### PASO 3: Verificar (2 min)

1. Railway construirá tu app
2. Verás en los logs:
   ```
   Running migrations...
   Seeding: RolSeeder
   Seeding: UserSeeder
   Seeding: CarreraSeeder
   ...
   Server started
   ```
3. Abre `https://tu-proyecto.up.railway.app`
4. Login con usuario de seeder
5. ✅ ¡Todo funciona!

---

## 🔍 ¿QUÉ PASA CON TU SQLite LOCAL?

```
SQLite local (C:\...\database.sqlite)
        ↓
    QUEDA INTACTO
        ↓
No se toca, no se copia, no se migra
        ↓
Tu desarrollo local sigue funcionando igual
```

**Railway y Local son INDEPENDIENTES:**

| Aspecto | Local | Railway |
|---------|-------|---------|
| Base de datos | SQLite | MySQL |
| Ubicación | Tu PC | Nube |
| Datos | Los que tienes | Se crean nuevos |
| Seeders | Ya ejecutados | Se ejecutan en Railway |

---

## 💡 VENTAJAS DE ESTE ENFOQUE

✅ **No hay migración de datos** (más simple)
✅ **Base de datos limpia** en producción
✅ **Datos consistentes** (de seeders)
✅ **Sin problemas de compatibilidad** SQLite → MySQL
✅ **Desarrollo local intacto** (sigues usando SQLite)
✅ **Fácil de reiniciar** (solo reejecutar seeders)

---

## 🔄 REDEPLOYS FUTUROS

### Primera vez (ahora):
```bash
migrate --force  # ← Crea tablas
db:seed --force  # ← Inserta datos
```

### Siguientes deployments (git push):
```bash
migrate --force  # ← Solo agrega nuevas tablas/columnas
# NO ejecuta seeders de nuevo (datos ya existen)
```

**Los seeders solo se ejecutan la primera vez.**

---

## 🎯 TU FLUJO DE TRABAJO

### Desarrollo Local (SQLite):
```bash
php artisan serve
# Trabajas normalmente
# Datos en database.sqlite
```

### Producción (Railway - MySQL):
```bash
git push origin main
# Railway auto-deploya
# Datos en MySQL en la nube
```

---

## 🆘 FAQ

### ❓ "¿Pierdo mis datos locales?"
**NO.** Tu SQLite local no se toca. Railway crea una BD nueva.

### ❓ "¿Tengo que exportar/importar datos?"
**NO.** Los seeders crean los datos en Railway automáticamente.

### ❓ "¿Puedo tener datos diferentes en local vs producción?"
**SÍ.** Son bases de datos completamente independientes.

### ❓ "¿Qué pasa si quiero resetear la BD de Railway?"
```bash
# Railway CLI
railway run php artisan migrate:fresh --seed
# O desde Railway Dashboard → Delete MySQL → Crear nuevo
```

### ❓ "¿Los seeders se ejecutan en cada deploy?"
**NO.** Solo la primera vez. Siguientes deploys respetan los datos existentes.

### ❓ "¿Puedo seguir usando SQLite local?"
**SÍ.** Tu `.env` local sigue con `DB_CONNECTION=sqlite`. No cambies nada local.

---

## ✅ CHECKLIST RÁPIDO

- [ ] Push código a GitHub
- [ ] Crear proyecto en Railway
- [ ] Agregar MySQL database (nueva, vacía)
- [ ] Configurar variables de entorno
- [ ] Configurar Build & Start commands
- [ ] Ver logs (migraciones + seeders)
- [ ] Abrir app en navegador
- [ ] Login con usuario de seeder
- [ ] ✅ ¡Todo funciona!

---

## 🎊 RESULTADO

```
┌──────────────────────────────────────┐
│                                      │
│  Local:                              │
│  ✓ SQLite (intacto)                 │
│  ✓ Tus datos de desarrollo          │
│  ✓ Funcionando como siempre          │
│                                      │
│  Railway (Producción):               │
│  ✓ MySQL (nuevo, vacío → con datos) │
│  ✓ Migraciones ejecutadas            │
│  ✓ Seeders ejecutados                │
│  ✓ Datos de prueba listos            │
│  ✓ App en línea                      │
│                                      │
└──────────────────────────────────────┘
```

---

## 🚀 SIGUIENTE PASO

```bash
# 1. Commit
git add .
git commit -m "feat: preparar Railway deployment"
git push origin main

# 2. Railway
# Ve a https://railway.app y sigue los pasos arriba

# 3. Verificar
# Abre tu app y login

# ¡LISTO! 🎉
```

---

**NO hay migración de datos.**
**Solo creas una BD nueva en Railway.**
**Los seeders la llenan automáticamente.**

**¡Súper simple!** 🚀
