# ⚡ CHECKLIST EXPRESS - 30 MINUTOS

---

## 📦 PASO 1: SUPABASE (5 min)

```
[ ] Ir a https://supabase.com
[ ] New Project
[ ] Name: hackathon-events
[ ] Password: __________ (ANOTAR)
[ ] Region: South America
[ ] Esperar 2 minutos

CUANDO TERMINE:
[ ] Settings > Database
[ ] Copiar HOST: ___________________
```

---

## 💻 PASO 2: GIT (3 min)

```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"

git add .
git commit -m "Deploy con Docker"
git push origin main
```

---

## 🚀 PASO 3: RENDER (5 min configurar + 15 min esperar)

```
[ ] Ir a https://render.com
[ ] Login con GitHub
[ ] New > Web Service
[ ] Conectar repo: hackathon-events
[ ] Name: hackathon-events
[ ] Environment: DOCKER ✅
[ ] Plan: Free
```

**VARIABLES DE ENTORNO:**
```
APP_KEY=base64:HpZthSqrslfh9UeEM0tc3jO/KYGOeCEMKKg2sti5ljA=
DB_CONNECTION=pgsql
DB_HOST=TU_HOST_SUPABASE
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=TU_PASSWORD_SUPABASE
DB_SSLMODE=require
APP_ENV=production
APP_DEBUG=false
```

```
[ ] Create Web Service
[ ] ESPERAR 15 minutos (ver logs)
```

---

## ✅ PASO 4: VERIFICAR (5 min)

```
[ ] Abrir URL: https://tu-app.onrender.com
[ ] Login admin: admin@hackathon.com / password
[ ] Login juez: juez1@hackathon.com / password
[ ] Login participante: juan.perez@alumno.com / password
[ ] Supabase > Table Editor > Ver datos
```

---

## 🎯 LISTO PARA MAÑANA

```
URL APP: ___________________________
PASSWORD SUPABASE: __________________

USUARIOS DEMO:
- Admin: admin@hackathon.com / password
- Juez: juez1@hackathon.com / password  
- Participante: juan.perez@alumno.com / password
```

---

## 🆘 SI HAY ERROR

```
Render Dashboard > Logs (ver qué falló)

Error común: DB connection
→ Verifica DB_HOST y DB_PASSWORD
→ Debe tener DB_SSLMODE=require
```

---

**TIEMPO TOTAL: 33 minutos**
**LEE: DEPLOY_DOCKER_RENDER.md para detalles**
