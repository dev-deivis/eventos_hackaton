# 🚀 Deploy a Producción con Supabase

## 📋 **Resumen**

Esta guía te muestra cómo:
- Mantener MySQL localmente para desarrollo
- Usar Supabase PostgreSQL en producción
- Desplegar a Railway, Render, o similar

---

## 🏗️ **Arquitectura**

```
DESARROLLO LOCAL          PRODUCCIÓN
┌──────────────┐         ┌──────────────┐
│   Laravel    │         │   Laravel    │
│  (tu PC)     │         │  (Railway)   │
└──────┬───────┘         └──────┬───────┘
       │                        │
       ▼                        ▼
┌──────────────┐         ┌──────────────┐
│    MySQL     │         │   Supabase   │
│  (XAMPP)     │         │  PostgreSQL  │
│  Puerto 3307 │         │              │
└──────────────┘         └──────────────┘
```

---

## ✅ **Paso 1: Preparar Supabase**

### 1.1 Crear Proyecto
1. Ve a [supabase.com](https://supabase.com)
2. Crea nuevo proyecto: `hackathon-events-prod`
3. Guarda la contraseña

### 1.2 Obtener Credenciales
En Supabase Dashboard:
- Settings > Database
- Copia: Host, Password

Ejemplo:
```
Host: db.abcdefghijk.supabase.co
Database: postgres
Username: postgres
Password: tu_password_guardado
Port: 5432
```

---

## ✅ **Paso 2: Preparar para Deploy**

### 2.1 Verificar Migraciones

```bash
# En tu proyecto local
php artisan migrate:status
```

Todas deben estar "Ran" ✅

### 2.2 Verificar Seeders

<parameter name="mode">rewrite
Abre `database/seeders/DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        CarreraSeeder::class,
        RolSeeder::class,
        PerfilSeeder::class,
        UserSeeder::class,
        EventoSeeder::class,
        EquipoSeeder::class,
    ]);
}
```

Verifica que existan todos los seeders listados ✅

---

## ✅ **Paso 3: Deploy a Railway** (Ejemplo)

### 3.1 Crear Cuenta en Railway
1. Ve a [railway.app](https://railway.app)
2. Inicia sesión con GitHub

### 3.2 Subir tu Código a GitHub (si no lo has hecho)

```bash
git init
git add .
git commit -m "Proyecto listo para deploy"
git branch -M main
git remote add origin https://github.com/tu-usuario/hackathon-events.git
git push -u origin main
```

### 3.3 Crear Proyecto en Railway

1. En Railway: "New Project" > "Deploy from GitHub repo"
2. Selecciona tu repositorio `hackathon-events`
3. Railway detectará Laravel automáticamente

### 3.4 Configurar Variables de Entorno

En Railway > Variables, agrega:

```env
APP_NAME=HackathonEvents
APP_ENV=production
APP_KEY=                        # Railway lo genera
APP_DEBUG=false
APP_URL=https://tu-app.railway.app

# Supabase PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.abcdefghijk.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_password_supabase
DB_SSLMODE=require

# Session y Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 3.5 Agregar Buildpack

En Railway > Settings > Build Command:
```bash
composer install --no-dev --optimize-autoloader && php artisan key:generate --force
```

En Railway > Settings > Deploy Command:
```bash
php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## ✅ **Paso 4: Deploy a Render** (Alternativa)

### 4.1 Crear Cuenta en Render
1. Ve a [render.com](https://render.com)
2. Inicia sesión con GitHub

### 4.2 Crear Web Service

1. "New" > "Web Service"
2. Conecta tu repositorio
3. Configura:
   - **Name:** hackathon-events
   - **Environment:** Docker
   - **Region:** Oregon (o el más cercano)
   - **Plan:** Free

### 4.3 Variables de Entorno

Agrega las mismas variables que en Railway (arriba)

### 4.4 Build Command

```bash
composer install && php artisan key:generate --force
```

### 4.5 Start Command

```bash
php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## ✅ **Paso 5: Verificar Deploy**

### 5.1 Verificar Migraciones

En Railway/Render, ve a Logs y busca:
```
INFO  Running migrations.
2024_01_01_000000_create_users_table ....... DONE
...
```

### 5.2 Verificar en Supabase

1. Supabase Dashboard > Table Editor
2. Deberías ver todas tus tablas
3. Verifica datos de seeders

### 5.3 Probar la Aplicación

1. Ve a tu URL: `https://tu-app.railway.app`
2. Intenta registrarte
3. Intenta login
4. Verifica funcionalidades básicas

---

## 🔧 **Solución de Problemas**

### **Error: "could not connect to server"**

✅ Verifica que `DB_SSLMODE=require` esté configurado  
✅ Verifica que el host de Supabase sea correcto  
✅ Verifica que la contraseña no tenga espacios  

### **Error: "SQLSTATE[42P01]: Undefined table"**

✅ Las migraciones no se ejecutaron  
✅ Verifica los logs del deploy  
✅ Ejecuta manualmente:

```bash
# En Railway/Render Shell
php artisan migrate:fresh --force
php artisan db:seed --force
```

### **Error: "Class DatabaseSeeder not found"**

✅ Ejecuta en producción:
```bash
composer dump-autoload
php artisan db:seed --force
```

### **La app funciona pero no hay datos**

✅ Los seeders no se ejecutaron  
✅ Ejecuta manualmente:
```bash
php artisan db:seed --force
```

---

## 📊 **Monitoreo**

### **Ver Logs en Tiempo Real**

**Railway:**
```
Railway Dashboard > tu-proyecto > Deployments > Logs
```

**Render:**
```
Render Dashboard > tu-servicio > Logs
```

### **Ver Base de Datos en Supabase**

```
Supabase Dashboard > Table Editor
```

Puedes:
- Ver todas las tablas
- Editar datos manualmente
- Ejecutar queries SQL
- Ver logs de conexiones

---

## 🔄 **Actualizar Producción**

Cada vez que hagas cambios:

```bash
# 1. Commit y push
git add .
git commit -m "Nuevas funcionalidades"
git push origin main

# 2. Railway/Render detectará y desplegará automáticamente
```

---

## 🎉 **¡Listo!**

Tu aplicación ahora está en producción con:
✅ Laravel funcionando  
✅ Supabase PostgreSQL  
✅ Deploy automático  
✅ HTTPS incluido  

---

## 📝 **Checklist Final**

- [ ] Proyecto creado en Supabase
- [ ] Credenciales copiadas
- [ ] Código subido a GitHub
- [ ] Proyecto creado en Railway/Render
- [ ] Variables de entorno configuradas
- [ ] Deploy ejecutado exitosamente
- [ ] Migraciones aplicadas
- [ ] Seeders ejecutados
- [ ] Aplicación accesible por HTTPS
- [ ] Login funciona
- [ ] Datos visibles en Supabase

---

**Siguiente:** [Configurar dominio personalizado](https://railway.app/docs/deploy/custom-domains)
