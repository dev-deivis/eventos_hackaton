# 📝 Resumen Ejecutivo: Deploy Railway + Supabase

## 🎯 Objetivo
Desplegar aplicación Laravel 11 (Hackathon Events) en Railway usando Supabase PostgreSQL como base de datos.

---

## 📊 Configuración Actual

### **Desarrollo Local:**
- **Framework:** Laravel 11
- **PHP:** 8.3 (Herd Lite)
- **Base de Datos:** MySQL (XAMPP Puerto 3307)
- **Frontend:** Tailwind CSS + Alpine.js + Vite

### **Producción:**
- **Hosting:** Railway (PaaS)
- **Base de Datos:** Supabase PostgreSQL
- **Deploy:** Automático desde GitHub
- **SSL/HTTPS:** Incluido automáticamente

---

## 🔑 Elementos Clave del Deploy

### **1. Archivo Principal: nixpacks.toml**
Configura todo el proceso de build en Railway:

```toml
[phases.setup]
nixPkgs = ['php83', 'composer', 'nodejs-18_x', 'postgresql']

[phases.install]
cmds = [
    'composer install --no-dev --optimize-autoloader',
    'npm ci --include=dev'
]

[phases.build]
cmds = [
    'npm run build',
    'composer dump-autoload --optimize'
]

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

**✅ Importante:**
- Railway usa Nixpacks para detectar Laravel
- Instala PHP 8.3 + extensiones (pgsql, intl, opcache)
- Compila assets frontend con Vite
- Optimiza autoloader para producción

---

### **2. Migración a Supabase: migrate-to-supabase.bat**

Script Windows que automatiza la migración de MySQL a PostgreSQL:

```batch
# 1. Respalda .env actual
copy .env .env.mysql.backup

# 2. Aplica configuración Supabase
copy .env.supabase .env

# 3. Limpia cache
php artisan config:clear

# 4. Verifica conexión
php artisan tinker --execute="DB::connection()..."

# 5. Ejecuta migraciones (⚠️ BORRA DATOS)
php artisan migrate:fresh --force

# 6. Carga datos iniciales
php artisan db:seed --force
```

**⚠️ Advertencias:**
- `migrate:fresh` BORRA todas las tablas existentes
- Útil para desarrollo, NO para producción con datos
- Siempre hacer backup antes de ejecutar

---

### **3. Variables Críticas para Railway**

```env
# Base de Datos Supabase
DB_CONNECTION=pgsql
DB_HOST=db.eispdmymfkisgwvydpxe.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=gari3000
DB_SSLMODE=require           # ⚠️ CRÍTICO

# Aplicación
APP_ENV=production
APP_DEBUG=false
APP_KEY=[generado por Railway]

# Session y Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

**🔑 Variables Obligatorias:**
- `DB_SSLMODE=require` → Sin esto falla la conexión
- `APP_KEY` → Railway lo genera automáticamente
- `DB_PASSWORD` → Contraseña del proyecto Supabase

---

## 🚀 Proceso de Deploy (Paso a Paso)

### **FASE 1: Preparar Supabase** ⏱️ 5 min

1. **Crear proyecto en Supabase:**
   - Ir a [supabase.com](https://supabase.com)
   - "New Project" → `hackathon-events`
   - Guardar contraseña generada

2. **Obtener credenciales:**
   - Settings > Database > Connection string
   - Copiar: Host, Port, Password

3. **Configurar .env.supabase:**
   ```env
   DB_HOST=db.xxxxx.supabase.co
   DB_PASSWORD=tu_password
   DB_SSLMODE=require
   ```

---

### **FASE 2: Probar Migración Local** ⏱️ 10 min

```bash
# Ejecutar script de migración
migrate-to-supabase.bat

# Verificar tablas en Supabase Dashboard
# Settings > Table Editor

# Probar aplicación localmente
php artisan serve
# Login: admin@test.com / password
```

**✅ Verificar que funcione:**
- Login exitoso
- Datos visibles en dashboard
- Sin errores en logs

---

### **FASE 3: Subir a GitHub** ⏱️ 3 min

```bash
git init
git add .
git commit -m "Proyecto listo para deploy"
git branch -M main
git remote add origin https://github.com/tu-usuario/hackathon-events.git
git push -u origin main
```

---

### **FASE 4: Configurar Railway** ⏱️ 5 min

1. **Crear proyecto:**
   - Ir a [railway.app](https://railway.app)
   - "New Project" > "Deploy from GitHub repo"
   - Seleccionar repositorio

2. **Configurar variables:**
   - Tab "Variables"
   - Copiar todas las variables de `.env.supabase`
   - Agregar `APP_URL=https://tu-proyecto.up.railway.app`

3. **Deploy automático:**
   - Railway detecta cambios en GitHub
   - Despliega automáticamente (~2-3 min)

---

### **FASE 5: Verificar Deploy** ⏱️ 5 min

1. **Ver logs:**
   - Railway Dashboard > Logs
   - Buscar: "Application ready"

2. **Probar aplicación:**
   - Abrir URL generada
   - Login: admin@test.com / password
   - Verificar funcionalidades

3. **Verificar Supabase:**
   - Table Editor > Ver datos
   - Logs > Ver conexiones

---

## ✅ Checklist de Verificación

### **Pre-Deploy:**
- [ ] Proyecto funciona localmente
- [ ] Migraciones probadas con Supabase local
- [ ] Código en GitHub
- [ ] `.env.example` actualizado

### **Supabase:**
- [ ] Proyecto creado
- [ ] Credenciales copiadas
- [ ] `DB_SSLMODE=require` configurado
- [ ] Tablas visibles en dashboard

### **Railway:**
- [ ] Proyecto creado
- [ ] Variables configuradas
- [ ] Deploy exitoso (sin errores)
- [ ] URL accesible

### **Aplicación:**
- [ ] Login funciona
- [ ] Datos visibles
- [ ] Correos funcionando (opcional)
- [ ] Assets cargando

---

## 🐛 Troubleshooting Rápido

### **Error: "could not connect to server"**
```env
# Solución: Verificar SSL
DB_SSLMODE=require
```

### **Error: "No encryption key"**
```bash
# Solución: Generar key
railway run php artisan key:generate --show
# Copiar a Railway Variables
```

### **Error: "Undefined table"**
```bash
# Solución: Ejecutar migraciones
railway shell
php artisan migrate:fresh --force
php artisan db:seed --force
```

### **Assets no cargan**
```toml
# Verificar en nixpacks.toml:
[phases.build]
cmds = ['npm run build']
```

---

## 📊 Arquitectura Simplificada

```
Usuario → Railway (Laravel) → Supabase (PostgreSQL)
          ↑
          └── GitHub (Auto-deploy en push)
```

---

## 🔄 Flujo de Actualización

```bash
# 1. Hacer cambios localmente
git add .
git commit -m "Nuevas funcionalidades"

# 2. Push a GitHub
git push origin main

# 3. Railway despliega automáticamente (2-3 min)
```

---

## 📈 Recursos y Límites

### **Plan Gratuito Supabase:**
- ✅ 500 MB base de datos
- ✅ 1 GB transferencia/mes
- ✅ Backups diarios automáticos
- ✅ Suficiente para 500+ usuarios

### **Plan Gratuito Railway:**
- ✅ $5 crédito mensual
- ✅ 500 horas ejecución
- ✅ Deploy automático desde GitHub
- ✅ HTTPS incluido

---

## 🎓 Documentación Relacionada

### **Guías Completas:**
- `ANALISIS_DEPLOYMENT.md` - Análisis técnico detallado
- `DIAGRAMA_DEPLOYMENT.md` - Diagramas visuales
- `GUIA_DEPLOY_RAILWAY.md` - Guía paso a paso Railway
- `GUIA_MIGRACION_SUPABASE.md` - Migración MySQL → PostgreSQL

### **Recursos Externos:**
- [Railway Docs](https://docs.railway.app/)
- [Supabase Docs](https://supabase.com/docs)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

## 💡 Recomendaciones Finales

### **✅ Hacer:**
1. Probar migración localmente primero
2. Hacer backup antes de deploy
3. Configurar `DB_SSLMODE=require`
4. Verificar logs después de deploy
5. Probar todas las funcionalidades críticas

### **❌ Evitar:**
1. Usar `migrate:fresh` en producción con datos
2. Olvidar `DB_SSLMODE=require`
3. Subir `.env` a GitHub
4. Desplegar sin probar localmente
5. Ignorar logs de errores

---

## 🚀 Próximos Pasos

Una vez desplegado exitosamente:

1. **Configurar dominio personalizado** (opcional)
2. **Activar correos con Brevo** (ya implementado)
3. **Configurar backups automáticos**
4. **Monitorear logs regularmente**
5. **Optimizar queries si es necesario**

---

## 📞 Soporte

**Si algo falla:**
1. Revisar logs de Railway
2. Verificar Supabase Dashboard
3. Consultar este documento
4. Revisar guías detalladas
5. Railway Discord / Supabase Discord

---

**Última Actualización:** Diciembre 2024  
**Tiempo Estimado Total:** ~30 minutos  
**Dificultad:** Media (con guías: Fácil)
