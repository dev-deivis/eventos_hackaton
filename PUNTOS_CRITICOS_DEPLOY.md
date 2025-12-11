# 🔥 Puntos Críticos y Mejores Prácticas - Deploy Railway + Supabase

## ⚠️ PUNTOS CRÍTICOS QUE CAUSAN FALLOS

### 1. **DB_SSLMODE=require** (El error #1)

```env
# ❌ INCORRECTO - Causará fallo de conexión
DB_SSLMODE=prefer
DB_SSLMODE=disable
# DB_SSLMODE sin configurar

# ✅ CORRECTO - Supabase EXIGE SSL
DB_SSLMODE=require
```

**Por qué es crítico:**
- Supabase PostgreSQL REQUIERE conexión SSL
- Sin esto, Railway no puede conectarse a la base de datos
- Error típico: `could not connect to server: Connection refused`

---

### 2. **APP_KEY en Railway** (El error #2)

```env
# ❌ INCORRECTO
APP_KEY=
# (vacío o sin configurar)

# ✅ CORRECTO
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Cómo generar:**
```bash
# Opción 1: Railway Shell
railway run php artisan key:generate --show

# Opción 2: Local
php artisan key:generate --show
```

**Por qué es crítico:**
- Laravel necesita APP_KEY para encriptar sesiones
- Sin esto, aparece: "No application encryption key has been specified"
- Railway NO genera automáticamente (a diferencia de otros servicios)

---

### 3. **migrate:fresh en Producción** (El error #3)

```bash
# ❌ PELIGROSO - Borra TODOS los datos
php artisan migrate:fresh --force

# ✅ SEGURO - Solo ejecuta migraciones pendientes
php artisan migrate --force
```

**Por qué es crítico:**
- `migrate:fresh` BORRA todas las tablas
- En desarrollo: OK (datos de prueba)
- En producción: CATASTRÓFICO (pérdida de datos)

**Cuándo usar cada uno:**
```bash
# Desarrollo / Primera vez
migrate:fresh --seed

# Producción / Actualizaciones
migrate
```

---

### 4. **Path de Herd Lite (Windows)** (El error #4)

```batch
# ❌ Sin esto, falla conexión PostgreSQL local
php artisan migrate

# ✅ Agregar Herd al PATH primero
set "PATH=C:\Users\LENOVO\.config\herd-lite\bin;%PATH%"
php artisan migrate
```

**Por qué es crítico:**
- Herd Lite incluye `libpq.dll` (driver PostgreSQL)
- PHP no encuentra la librería sin el PATH correcto
- Error típico: `could not find driver` al conectar a PostgreSQL

---

### 5. **Assets no Compilados** (El error #5)

```toml
# ❌ INCORRECTO - Assets no se compilan
[phases.build]
cmds = [
    'composer dump-autoload'
]

# ✅ CORRECTO - Compila assets frontend
[phases.build]
cmds = [
    'npm run build',
    'composer dump-autoload --optimize'
]
```

**Por qué es crítico:**
- Sin `npm run build`, CSS/JS no existen en producción
- Vite debe compilar assets antes de deploy
- Error típico: 404 en archivos CSS/JS

---

## 🎯 Mejores Prácticas

### **1. Gestión de Ambientes**

```
.env               → Local (MySQL)
.env.supabase      → Testing (PostgreSQL)
.env.production    → Producción (no usar, usar Railway Variables)
.env.mysql.backup  → Respaldo automático
```

**Flujo recomendado:**
```bash
# 1. Desarrollar con MySQL
cp .env.example .env
php artisan serve

# 2. Probar con PostgreSQL
migrate-to-supabase.bat
php artisan serve

# 3. Si funciona, desplegar
git push origin main
```

---

### **2. Versionamiento de Migraciones**

```php
// ✅ BUENA práctica - Nombres descriptivos
2024_12_01_000001_create_eventos_table.php
2024_12_01_000002_add_estado_to_equipos.php
2024_12_02_000001_create_evaluaciones_table.php

// ❌ MALA práctica - Nombres genéricos
migration_1.php
update_table.php
```

**Por qué importa:**
- Facilita seguimiento de cambios
- Ayuda en rollbacks
- Documentación implícita

---

### **3. Seeders Seguros**

```php
// ✅ BUENA práctica - Verificar antes de crear
if (!User::where('email', 'admin@test.com')->exists()) {
    User::create([
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
    ]);
}

// ❌ MALA práctica - Crear sin verificar
User::create([
    'email' => 'admin@test.com',
    'password' => Hash::make('password'),
]);
```

**Por qué importa:**
- Evita duplicados en re-ejecuciones
- Permite ejecutar seeders múltiples veces
- Útil cuando hay datos en producción

---

### **4. Variables de Entorno en Railway**

```env
# ✅ BUENA práctica - Usar variables específicas
DB_CONNECTION=pgsql
DB_HOST=${SUPABASE_HOST}
DB_PASSWORD=${SUPABASE_PASSWORD}

# ❌ MALA práctica - Hardcodear valores
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PASSWORD=gari3000
```

**Ventajas:**
- Fácil cambiar entre ambientes
- No exponer credenciales en código
- Railway permite referencias ${VAR}

---

### **5. Logging y Monitoreo**

```php
// ✅ BUENA práctica - Logs contextuales
Log::info('Usuario creado', [
    'user_id' => $user->id,
    'email' => $user->email,
    'rol' => $user->rol->nombre
]);

// ❌ MALA práctica - Logs sin contexto
Log::info('Usuario creado');
```

**Configuración Railway:**
```env
# Producción
LOG_LEVEL=error
LOG_CHANNEL=stack

# Desarrollo
LOG_LEVEL=debug
LOG_CHANNEL=single
```

---

## 🔒 Seguridad

### **1. Nunca Subir .env a GitHub**

```gitignore
# ✅ Verificar que esté en .gitignore
.env
.env.backup
.env.*
!.env.example
```

**Verificar:**
```bash
git status
# .env NO debe aparecer

git check-ignore .env
# Debe retornar: .env
```

---

### **2. Credenciales en Producción**

```env
# ✅ BUENA práctica - Railway Variables
DB_PASSWORD=${SUPABASE_PASSWORD}
MAIL_PASSWORD=${BREVO_API_KEY}

# ❌ MALA práctica - Hardcodear
DB_PASSWORD=gari3000
MAIL_PASSWORD=xkeysib-xxxxx
```

**Rotar credenciales regularmente:**
- Supabase: Settings > Database > Reset Password
- Brevo: API Keys > Regenerate
- Railway: Actualizar variables

---

### **3. Modo Debug en Producción**

```env
# ✅ CORRECTO - Producción
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

# ❌ PELIGROSO - Expone stack traces
APP_ENV=production
APP_DEBUG=true
```

**Por qué es crítico:**
- `APP_DEBUG=true` expone código fuente
- Muestra rutas completas del servidor
- Revela estructura de base de datos

---

## 📊 Optimizaciones

### **1. OPcache (Incluido en nixpacks.toml)**

```php
# Ya configurado en nixpacks.toml
php -d opcache.enable=1
php -d opcache.memory_consumption=128
php -d opcache.max_accelerated_files=10000
```

**Beneficios:**
- 3-5x más rápido que sin cache
- Reduce uso de CPU
- Incluido en Railway automáticamente

---

### **2. Cache de Configuración**

```bash
# ✅ En producción - Siempre cachear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ❌ En desarrollo - NO cachear
# (dificulta debugging)
```

**Cuándo cachear:**
- Producción: Siempre
- Staging: Opcional
- Desarrollo: Nunca

---

### **3. Eager Loading (N+1 Problem)**

```php
// ❌ MALO - 1 query + N queries (N+1 problem)
$equipos = Equipo::all();
foreach($equipos as $equipo) {
    echo $equipo->evento->nombre; // Query extra por cada equipo
}

// ✅ BUENO - Solo 2 queries
$equipos = Equipo::with('evento')->get();
foreach($equipos as $equipo) {
    echo $equipo->evento->nombre;
}
```

**Herramientas para detectar:**
```bash
composer require barryvdh/laravel-debugbar --dev
```

---

### **4. Índices en Base de Datos**

```php
// ✅ Agregar índices a columnas frecuentemente buscadas
Schema::table('eventos', function (Blueprint $table) {
    $table->index('estado');
    $table->index('fecha_inicio');
    $table->index(['evento_id', 'user_id']); // Índice compuesto
});
```

**Cuándo agregar índices:**
- Columnas en WHERE
- Columnas en JOIN
- Foreign keys
- Columnas en ORDER BY

---

## 🧪 Testing Antes de Deploy

### **Checklist Pre-Deploy:**

```bash
# 1. Tests automatizados
php artisan test

# 2. Verificar migraciones
php artisan migrate:status

# 3. Probar con PostgreSQL localmente
migrate-to-supabase.bat

# 4. Compilar assets
npm run build

# 5. Verificar rutas
php artisan route:list

# 6. Limpiar cache
php artisan cache:clear
php artisan config:clear

# 7. Verificar .env.example actualizado
diff .env .env.example
```

---

## 🔄 Rollback Plan

### **Si algo sale mal en producción:**

```bash
# 1. Acceder a Railway Shell
railway shell

# 2. Ver últimas migraciones
php artisan migrate:status

# 3. Rollback última migración
php artisan migrate:rollback

# 4. O rollback múltiples
php artisan migrate:rollback --step=3

# 5. O restaurar backup de Supabase
# Settings > Database > Backups > Restore
```

---

### **Plan B: Redeploy versión anterior**

```bash
# 1. Ver commits anteriores
git log --oneline

# 2. Revertir a commit específico
git revert abc123

# 3. Push para redeploy
git push origin main

# Railway desplegará versión anterior
```

---

## 📋 Checklist de Verificación Post-Deploy

### **Inmediatamente después de deploy:**

- [ ] URL accesible (https://xxx.railway.app)
- [ ] Login funciona
- [ ] Datos visibles en dashboard
- [ ] Sin errores en Railway Logs
- [ ] CSS/JS cargando correctamente
- [ ] Imágenes funcionando
- [ ] Formularios enviando datos
- [ ] Base de datos conectada (verificar en Supabase)

### **24 horas después:**

- [ ] Monitorear logs de errores
- [ ] Verificar uso de recursos (Railway Dashboard)
- [ ] Comprobar velocidad de respuesta
- [ ] Revisar backups en Supabase
- [ ] Verificar correos funcionando (si aplica)

---

## 🚨 Señales de Alerta

### **Indicadores de problemas:**

1. **Logs con errores frecuentes:**
   ```
   SQLSTATE[08006] Connection refused
   → Problema con Supabase
   
   SQLSTATE[42P01] Undefined table
   → Migraciones no ejecutadas
   
   No application encryption key
   → APP_KEY no configurado
   ```

2. **Alto uso de base de datos:**
   - Supabase Dashboard > Database > Usage
   - Si > 80%, considerar upgrade o optimizar queries

3. **Tiempos de respuesta lentos:**
   - Railway Logs > Filtrar por tiempo de respuesta
   - Si > 2 segundos, revisar queries

---

## 💡 Tips Avanzados

### **1. Múltiples Conexiones DB**

```php
// config/database.php
'connections' => [
    'mysql' => [...],
    'pgsql' => [...],
],

// Usar conexión específica
DB::connection('mysql')->table('users')->get();
DB::connection('pgsql')->table('eventos')->get();
```

---

### **2. Queue Workers en Railway**

```procfile
web: php artisan serve --host=0.0.0.0 --port=$PORT
worker: php artisan queue:work --sleep=3 --tries=3
```

**Configurar en Railway:**
- Crear nuevo servicio: "Worker"
- Usar mismo repositorio
- Cambiar start command a: `php artisan queue:work`

---

### **3. Scheduled Tasks (Cron)**

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('eventos:check-estados')
             ->hourly();
}
```

**Railway no soporta cron nativo:**
- Usar [cron-job.org](https://cron-job.org)
- Llamar endpoint: `https://tu-app.railway.app/api/cron`

---

## 📚 Recursos Adicionales

### **Documentación Oficial:**
- [Laravel Deployment](https://laravel.com/docs/11.x/deployment)
- [Railway Docs](https://docs.railway.app/)
- [Supabase Docs](https://supabase.com/docs)

### **Comunidades:**
- [Railway Discord](https://discord.gg/railway)
- [Supabase Discord](https://discord.supabase.com/)
- [Laravel Discord](https://discord.gg/laravel)

### **Herramientas Útiles:**
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Railway CLI](https://docs.railway.app/develop/cli)
- [Supabase CLI](https://supabase.com/docs/guides/cli)

---

**Última Actualización:** Diciembre 2024  
**Autor:** Análisis de Mejores Prácticas  
**Versión:** 1.0
