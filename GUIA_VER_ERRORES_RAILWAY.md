# 🐛 GUÍA: Ver Errores en Railway (Producción)

## 🎯 PROBLEMA

**Localhost:** Muestra errores detallados (APP_DEBUG=true)
**Railway:** Solo muestra página genérica de error (APP_DEBUG=false)

## ✅ SOLUCIONES (3 OPCIONES)

---

## **OPCIÓN 1: Ver Logs de Railway** (RECOMENDADO ⭐)

La forma **CORRECTA** y **SEGURA** en producción.

### Método A: Desde CLI (Railway CLI)

**1. Instalar Railway CLI** (si no lo tienes):
```bash
# Windows (PowerShell)
iwr https://railway.app/install.ps1 | iex

# Verificar instalación
railway --version
```

**2. Login:**
```bash
railway login
```

**3. Linkear proyecto:**
```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"
railway link
# Selecciona tu proyecto "hackathon-events"
```

**4. Ver logs en tiempo real:**
```bash
railway logs --tail
```

O usa el script que creé:
```bash
ver-logs-railway.bat
```

### Método B: Desde Dashboard Web

1. Ir a: https://railway.app
2. Abrir proyecto "hackathon-events"
3. Click en "Logs" (arriba a la derecha)
4. Ver errores en tiempo real

**Ejemplo de lo que verás:**
```
[2024-12-07 10:30:15] production.ERROR: 
SQLSTATE[23000]: Integrity constraint violation
File: app/Http/Controllers/EventoController.php:431
```

---

## **OPCIÓN 2: Habilitar DEBUG temporalmente** (SOLO PARA DEBUGGING)

⚠️ **PELIGRO:** Nunca dejes DEBUG=true en producción por mucho tiempo.

### Pasos:

**1. En Railway Dashboard:**
```
1. Variables de Entorno
2. Encontrar APP_DEBUG
3. Cambiar de "false" a "true"
4. Click "Deploy"
```

**2. Reproducir el error:**
```
1. Ir a la app en Railway
2. Intentar editar evento con error
3. Ver error detallado en pantalla
```

**3. IMPORTANTE - Desactivar después:**
```
1. Volver a Variables
2. APP_DEBUG = "false"
3. Deploy
```

---

## **OPCIÓN 3: Mejorar Mensajes de Error** (MEJOR PRÁCTICA)

En lugar de depender de errores técnicos, mejorar la validación.

### Ya implementado en tu código:

```php
$validated = $request->validate([
    'nombre' => 'required|max:35',
    'descripcion' => 'required|max:150',
    // ... más validaciones
], [
    'nombre.required' => 'El nombre del evento es obligatorio.',
    'nombre.max' => 'El nombre no puede tener más de 35 caracteres.',
    // Mensajes personalizados
]);
```

### Estos mensajes SÍ se muestran en producción:

```php
// En tu vista (ya está implementado)
@error('nombre')
    <span class="text-red-500">{{ $message }}</span>
@enderror
```

---

## 📋 COMPARACIÓN DE OPCIONES

| Opción | Seguridad | Facilidad | Producción | Recomendado |
|--------|-----------|-----------|------------|-------------|
| **Railway Logs** | ✅ Alta | ⭐⭐⭐ | ✅ Sí | ⭐⭐⭐⭐⭐ |
| **DEBUG=true** | ❌ Baja | ⭐⭐⭐⭐⭐ | ⚠️ Temporal | ⭐ |
| **Validaciones** | ✅ Alta | ⭐⭐⭐⭐ | ✅ Sí | ⭐⭐⭐⭐ |

---

## 🔍 CÓMO LEER LOS LOGS

### Formato de error típico:

```log
[timestamp] environment.ERROR: Mensaje del error

Exception: Illuminate\Database\QueryException
File: app/Http/Controllers/EventoController.php:431
Line: 431
Message: SQLSTATE[23000]: Integrity constraint violation

Stack trace:
#0 vendor/laravel/framework/...
#1 app/Http/Controllers/EventoController.php(431)
```

### Lo importante:
- **Exception:** Tipo de error
- **File:** Archivo donde ocurrió
- **Line:** Línea exacta
- **Message:** Descripción del error

---

## 🛠️ SCRIPT CREADO: ver-logs-railway.bat

Ya creé un script para ti:

```batch
@echo off
echo ========================================
echo VER LOGS DE RAILWAY EN TIEMPO REAL
echo ========================================
railway logs --tail
```

**Uso:**
```bash
# En la carpeta del proyecto
ver-logs-railway.bat
```

---

## 💡 TIPS PARA DEBUGGING EN RAILWAY

### 1. **Ver logs específicos:**
```bash
# Últimas 100 líneas
railway logs

# Últimas 500 líneas
railway logs --limit 500

# Solo errores
railway logs --filter error

# En tiempo real
railway logs --tail
```

### 2. **Agregar logs personalizados:**

En tu código:
```php
use Illuminate\Support\Facades\Log;

// En EventoController.php
public function update(Request $request, Evento $evento)
{
    try {
        Log::info('Actualizando evento', [
            'evento_id' => $evento->id,
            'data' => $request->all()
        ]);
        
        // Tu código aquí
        
    } catch (\Exception $e) {
        Log::error('Error al actualizar evento', [
            'evento_id' => $evento->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->withErrors([
            'error' => 'Error al actualizar el evento. Revisa los logs.'
        ])->withInput();
    }
}
```

### 3. **Verificar variables de entorno:**
```bash
railway variables
```

---

## 🎯 WORKFLOW RECOMENDADO

### Cuando algo falla en Railway:

1. **Reproducir el error**
   - Hacer la acción que causa el error
   - Anotar la hora exacta

2. **Ver logs:**
   ```bash
   railway logs --tail
   ```

3. **Buscar el error:**
   - Buscar por la hora
   - Identificar Exception
   - Ver archivo y línea

4. **Corregir localmente:**
   - Reproducir en localhost
   - Corregir el bug
   - Probar localmente

5. **Deploy:**
   ```bash
   git add .
   git commit -m "fix: Descripción del fix"
   git push origin main
   ```

6. **Verificar en Railway:**
   - Esperar deploy
   - Probar nuevamente
   - Verificar logs sin errores

---

## 📚 RECURSOS

### Railway CLI:
- Docs: https://docs.railway.app/develop/cli
- Install: https://railway.app/install

### Laravel Logging:
- Docs: https://laravel.com/docs/12.x/logging
- Channels: stack, single, daily, slack, etc.

---

## ⚠️ NUNCA EN PRODUCCIÓN

```env
# ❌ NUNCA dejar así en Railway
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug

# ✅ SIEMPRE en Railway
APP_DEBUG=false
APP_ENV=production
LOG_LEVEL=error
```

---

## 🎉 RESUMEN

**Para ver errores en Railway:**
1. ⭐ **MEJOR:** `railway logs --tail`
2. ⚠️ **Temporal:** APP_DEBUG=true (desactivar después)
3. ✅ **Prevención:** Validaciones con mensajes claros

**Tu proyecto ya tiene:**
- ✅ Validaciones completas
- ✅ Mensajes de error personalizados
- ✅ Script ver-logs-railway.bat

**Próximo paso:**
```bash
ver-logs-railway.bat
```

---

**Creado:** 7 de Diciembre, 2025
**Propósito:** Debugging en Railway sin comprometer seguridad
**Recomendación:** Usa Railway logs, no APP_DEBUG=true
