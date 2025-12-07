# 🚀 GUÍA COMPLETA: DESPLEGAR EN RENDER DESDE CERO

## 🎯 OPCIÓN 1: MÉTODO SIMPLE (SIN DOCKER) - RECOMENDADO

Este método es más fácil y rápido para empezar.

---

## PASO 1: SUBIR TU PROYECTO A GITHUB

### A. Crear Repositorio en GitHub

1. **Ve a:** [https://github.com/new](https://github.com/new)
2. **Nombre del repositorio:** `eventos_hackaton`
3. **Descripción:** "Sistema de gestión de eventos hackathon - TecNM"
4. **Visibilidad:** 
   - `Public` (si quieres que sea público)
   - `Private` (si quieres que sea privado - requiere verificar cuenta en Render)
5. **NO marques:** "Initialize this repository with a README"
6. **Clic en:** "Create repository"

---

### B. Conectar tu Proyecto Local con GitHub

Abre tu terminal en la carpeta del proyecto:

```bash
cd C:\Users\diego\Downloads\eventos_hackaton
```

Ejecuta estos comandos UNO POR UNO:

```bash
# 1. Inicializar Git (si no está inicializado)
git init

# 2. Agregar todos los archivos
git add .

# 3. Hacer commit inicial
git commit -m "Initial commit - Sistema de eventos hackathon"

# 4. Conectar con tu repositorio (REEMPLAZA con TU URL)
git remote add origin https://github.com/TU-USUARIO/eventos_hackaton.git

# 5. Cambiar a rama main
git branch -M main

# 6. Subir todo a GitHub
git push -u origin main
```

**¿Te pide usuario y contraseña?**
- Usa tu **Personal Access Token** de GitHub
- [Cómo crear un token](https://github.com/settings/tokens)

---

## PASO 2: CREAR PROYECTO EN RENDER

### A. Crear Cuenta en Render

1. **Ve a:** [https://render.com](https://render.com)
2. **Clic en:** "Get Started for Free"
3. **Opciones:**
   - Sign up with GitHub (RECOMENDADO)
   - O usa email

---

### B. Conectar con GitHub

1. **En Render Dashboard**, clic en: "New +"
2. **Selecciona:** "Web Service"
3. **Clic en:** "Connect account" (si es primera vez)
4. **Autoriza Render** en GitHub
5. **Selecciona:**
   - "All repositories" (todos tus repos)
   - O "Only select repositories" → selecciona `eventos_hackaton`

---

### C. Configurar el Web Service

Render te mostrará un formulario:

#### **1. Información Básica:**
```
Name: eventos-hackathon
```

#### **2. Build Settings:**
```
Environment: PHP
Region: Oregon (US West)
Branch: main
Root Directory: (dejar vacío)
```

#### **3. Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

#### **4. Start Command:**
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## PASO 3: CONFIGURAR VARIABLES DE ENTORNO

Antes de hacer deploy, necesitas configurar las variables.

### A. Agregar Variables de Entorno

En la sección **Environment Variables**, clic en "Add Environment Variable".

Agrega TODAS estas variables (copia y pega):

```
APP_NAME=Eventos Hackathon TecNM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://eventos-hackathon.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

# Base de datos (Render te dará una PostgreSQL gratis)
DB_CONNECTION=pgsql
DB_HOST=[LO OBTENDRÁS DE RENDER DATABASE]
DB_PORT=5432
DB_DATABASE=eventos_hackaton
DB_USERNAME=eventos_hackaton_user
DB_PASSWORD=[LO OBTENDRÁS DE RENDER DATABASE]

# Sesión y Caché
SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

# Email - Brevo SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=eventos.contacto.web@gmail.com
MAIL_PASSWORD=lxxx gyrq bgrn ubty
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=eventos.contacto.web@gmail.com
MAIL_FROM_NAME=Eventos Hackathon TecNM
```

**⚠️ IMPORTANTE:** NO agregues `APP_KEY` - Render lo generará automáticamente.

---

## PASO 4: CREAR BASE DE DATOS EN RENDER

### A. Crear PostgreSQL Database

1. **En Render Dashboard**, clic en: "New +" → "PostgreSQL"
2. **Configuración:**
   ```
   Name: eventos-hackathon-db
   Database: eventos_hackaton
   User: eventos_hackaton_user
   Region: Oregon (mismo que el web service)
   Plan: Free
   ```
3. **Clic en:** "Create Database"

---

### B. Obtener Credenciales de la Base de Datos

1. **En tu base de datos**, ve a: "Info"
2. **Copia estos valores:**
   - **Hostname (Internal Database URL):** `dpg-xxxxx-a.oregon-postgres.render.com`
   - **Port:** `5432`
   - **Database:** `eventos_hackaton`
   - **Username:** `eventos_hackaton_user`
   - **Password:** (clic en "Show" para verla)

---

### C. Actualizar Variables en Web Service

1. **Vuelve a tu Web Service**
2. **Ve a:** Environment
3. **Actualiza estas variables:**
   ```
   DB_HOST=[el hostname que copiaste]
   DB_PASSWORD=[la password que copiaste]
   ```
4. **Clic en:** "Save Changes"

---

## PASO 5: DESPLEGAR

### A. Iniciar Deploy

1. **Render empezará a desplegar automáticamente**
2. **Verás los logs en tiempo real**
3. **Espera 5-10 minutos**

---

### B. Monitorear el Deploy

En los logs verás algo como:

```
==> Cloning from https://github.com/tu-usuario/eventos_hackaton...
==> Running build command...
==> Installing dependencies...
==> Building assets...
==> Starting server...
==> Your service is live at https://eventos-hackathon.onrender.com
```

---

## PASO 6: EJECUTAR MIGRACIONES

### Opción A: Desde el Dashboard

1. **En tu Web Service**, ve a: "Shell"
2. **Ejecuta:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=DatabaseSeeder --force
   ```

### Opción B: Configurar en Start Command

Ya está configurado en el start command:
```bash
php artisan migrate --force && php artisan serve...
```

---

## PASO 7: VERIFICAR QUE TODO FUNCIONA

### A. Abrir la Aplicación

1. **En Render**, clic en tu URL: `https://eventos-hackathon.onrender.com`
2. **Deberías ver** tu aplicación funcionando

---

### B. Probar Funcionalidades

1. **Registrar un usuario** → Debería llegar email de bienvenida
2. **Crear un evento (admin)** → Deberían llegar emails a participantes
3. **Unirse a equipo** → Debería llegar email al líder

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Application Error"

**Causa:** Problema con las variables de entorno

**Solución:**
1. Ve a: Environment
2. Verifica que TODAS las variables estén configuradas
3. Genera un nuevo `APP_KEY`:
   ```bash
   php artisan key:generate --show
   ```
4. Agrega la key como variable de entorno

---

### Error: "Database connection failed"

**Causa:** Credenciales incorrectas

**Solución:**
1. Ve a tu PostgreSQL database
2. Copia las credenciales correctas
3. Actualiza las variables en el Web Service
4. Redeploy

---

### Error: "Build failed"

**Causa:** Problema con las dependencias

**Solución:**
1. Verifica que `composer.json` esté en GitHub
2. Verifica que `package.json` esté en GitHub
3. Revisa los logs para ver el error específico

---

### Los emails no se envían

**Causa:** Variables de email no configuradas

**Solución:**
1. Verifica las 8 variables de `MAIL_*`
2. Verifica que Brevo esté configurado
3. Revisa los logs: `php artisan tinker` → probar email

---

## 📊 MONITOREO Y MANTENIMIENTO

### Ver Logs

1. **En Render**, ve a: "Logs"
2. **Verás logs en tiempo real**
3. **Busca errores** con Ctrl+F

---

### Actualizar la Aplicación

Cada vez que hagas cambios:

```bash
git add .
git commit -m "Descripción de los cambios"
git push origin main
```

**Render redesplegará automáticamente** 🚀

---

## ✅ CHECKLIST FINAL

### Antes del Deploy:
- [ ] Proyecto subido a GitHub
- [ ] Cuenta de Render creada
- [ ] Cuenta de Brevo configurada
- [ ] Remitente verificado en Brevo

### En Render:
- [ ] Web Service creado
- [ ] PostgreSQL database creada
- [ ] Todas las variables configuradas
- [ ] Migraciones ejecutadas

### Pruebas:
- [ ] Aplicación carga correctamente
- [ ] Registro de usuario funciona
- [ ] Email de bienvenida llega
- [ ] Login funciona
- [ ] Dashboard carga

---

## 🎯 COSTOS

### Plan Gratuito de Render incluye:

- ✅ **Web Service:** Gratis (con limitaciones)
  - 750 horas/mes
  - Se duerme después de 15 min sin actividad
  - 512 MB RAM
  
- ✅ **PostgreSQL:** Gratis
  - 1 GB de almacenamiento
  - Expira después de 90 días (puedes crear otra)

### Para Producción Real (Opcional):

- **Starter Plan:** $7/mes
  - Sin suspensión
  - 512 MB RAM
  - SSL incluido

---

## 📝 SIGUIENTE PASO

¿En qué parte estás?

**OPCIÓN A:** Ya tengo el proyecto en GitHub
→ Salta al PASO 2

**OPCIÓN B:** NO tengo el proyecto en GitHub
→ Ejecuta: `subir_a_github.bat` (ya creado)

**OPCIÓN C:** Tengo dudas sobre algo específico
→ ¡Pregúntame!

---

¿Empezamos con el PASO 1 (GitHub) o ya lo tienes? 🚀
