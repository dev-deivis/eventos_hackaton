# 🚀 GUÍA RÁPIDA: 5 PASOS PARA DESPLEGAR EN RENDER

## ⏱️ TIEMPO TOTAL: 20 MINUTOS

---

## 📝 PASO 1: GITHUB (5 min)

### A. Crear Repositorio
1. Ve a: [github.com/new](https://github.com/new)
2. Nombre: `eventos_hackaton`
3. Public o Private
4. Clic: "Create repository"

### B. Subir Código
```bash
git init
git add .
git commit -m "Sistema de eventos hackathon"
git remote add origin https://github.com/TU-USUARIO/eventos_hackaton.git
git branch -M main
git push -u origin main
```

---

## 🌐 PASO 2: CREAR WEB SERVICE EN RENDER (3 min)

1. Ve a: [render.com](https://render.com)
2. Sign up with GitHub
3. Clic: "New +" → "Web Service"
4. Conecta tu repositorio `eventos_hackaton`
5. Configuración:
   ```
   Name: eventos-hackathon
   Environment: PHP
   Build: composer install && npm run build
   Start: php artisan serve --host=0.0.0.0 --port=$PORT
   ```
6. **NO HAGAS DEPLOY TODAVÍA**

---

## 🗄️ PASO 3: CREAR BASE DE DATOS (2 min)

1. Clic: "New +" → "PostgreSQL"
2. Configuración:
   ```
   Name: eventos-hackathon-db
   Database: eventos_hackaton
   Region: Oregon
   ```
3. Clic: "Create Database"
4. **Copia las credenciales** (las necesitarás)

---

## ⚙️ PASO 4: CONFIGURAR VARIABLES (5 min)

En tu Web Service → Environment, agrega:

```env
APP_NAME=Eventos Hackathon
APP_ENV=production
APP_DEBUG=false
APP_URL=https://eventos-hackathon.onrender.com

DB_CONNECTION=pgsql
DB_HOST=[copia de tu DB]
DB_PORT=5432
DB_DATABASE=eventos_hackaton
DB_USERNAME=[copia de tu DB]
DB_PASSWORD=[copia de tu DB]

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=eventos.contacto.web@gmail.com
MAIL_PASSWORD=lxxx gyrq bgrn ubty
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=eventos.contacto.web@gmail.com
MAIL_FROM_NAME=Eventos Hackathon TecNM
```

---

## 🚀 PASO 5: DESPLEGAR (5 min)

1. Clic: "Create Web Service"
2. Espera 5-10 minutos
3. Cuando termine, ve a: Shell
4. Ejecuta:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
5. ¡Abre tu app! 🎉

---

## ✅ VERIFICAR

- [ ] App carga: `https://eventos-hackathon.onrender.com`
- [ ] Registro funciona
- [ ] Email de bienvenida llega
- [ ] Login funciona

---

## 🆘 SI HAY PROBLEMAS

### App no carga
→ Revisa Logs en Render
→ Verifica variables de entorno

### Base de datos falla
→ Verifica credenciales DB
→ Ejecuta migraciones en Shell

### Emails no llegan
→ Verifica configuración Brevo
→ Revisa spam

---

## 📚 GUÍAS COMPLETAS

- `DESPLEGAR_RENDER_COMPLETO.md` - Guía detallada paso a paso
- `GUIA_RENDER_BREVO_COMPLETA.md` - Configurar emails
- `SISTEMA_EMAILS_COMPLETO.md` - Sistema de notificaciones

---

¿Listo para empezar? ¡Vamos! 🚀
