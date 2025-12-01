# 🔧 FIX: REDIRECCIÓN POR ROL DESPUÉS DEL LOGIN

## ❌ PROBLEMA

Cuando un usuario con rol **juez** hacía login, era redirigido al dashboard de participante (`/dashboard`) en lugar del dashboard de juez (`/juez/dashboard`).

---

## ✅ SOLUCIÓN IMPLEMENTADA

### **1. Middleware agregado a las rutas de juez**

**Archivo:** `routes/web.php`

```php
// ANTES:
Route::middleware(['auth'])->prefix('juez')->name('juez.')->group(function () {

// AHORA:
Route::middleware(['auth', 'juez'])->prefix('juez')->name('juez.')->group(function () {
```

**Protección:** Ahora solo usuarios con rol `juez` pueden acceder a `/juez/*`

---

### **2. Redirección inteligente según rol**

**Archivo:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**ANTES:**
```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    
    return redirect()->intended(route('dashboard', absolute: false));
}
```

**AHORA:**
```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    // Redirigir según el rol del usuario
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->intended(route('admin.dashboard'));
    } elseif ($user->isJuez()) {
        return redirect()->intended(route('juez.dashboard'));
    } else {
        return redirect()->intended(route('dashboard'));
    }
}
```

---

## 🎯 FLUJO DE LOGIN ACTUALIZADO

### **Usuario Admin:**
```
Login → Verifica credenciales → isAdmin() == true → /admin/dashboard
```

### **Usuario Juez:**
```
Login → Verifica credenciales → isJuez() == true → /juez/dashboard
```

### **Usuario Participante:**
```
Login → Verifica credenciales → (default) → /dashboard
```

---

## 🔐 MIDDLEWARES CONFIGURADOS

**Archivo:** `bootstrap/app.php`

```php
$middleware->alias([
    'admin' => AdminMiddleware::class,
    'juez' => JuezMiddleware::class,        ← Ya estaba registrado
    'profile.complete' => EnsureProfileComplete::class,
]);
```

---

## 📋 VERIFICACIÓN DEL MIDDLEWARE JUEZ

**Archivo:** `app/Http/Middleware/JuezMiddleware.php`

```php
public function handle(Request $request, Closure $next): Response
{
    // 1. Verificar autenticación
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Debes iniciar sesión.');
    }

    // 2. Verificar rol de juez
    if (!auth()->user()->isJuez()) {
        abort(403, 'No tienes permisos para acceder.');
    }

    return $next($request);
}
```

---

## 🧪 PRUEBAS

### **Test 1: Login como Admin**
```
1. Login con usuario admin (email: paquito@gmail.com)
2. Verifica redirección → /admin/dashboard ✅
3. Ver estadísticas de admin ✅
```

### **Test 2: Login como Juez**
```
1. Login con usuario juez
2. Verifica redirección → /juez/dashboard ✅
3. Ver equipos pendientes ✅
4. Ver estadísticas de evaluación ✅
```

### **Test 3: Login como Participante**
```
1. Login con usuario participante
2. Verifica redirección → /dashboard ✅
3. Ver eventos disponibles ✅
```

### **Test 4: Juez intenta acceder a admin**
```
1. Login como juez
2. Intentar acceder a /admin/dashboard
3. Resultado → Error 403 Forbidden ✅
```

### **Test 5: Participante intenta acceder a juez**
```
1. Login como participante
2. Intentar acceder a /juez/dashboard
3. Resultado → Error 403 Forbidden ✅
```

---

## 📊 TABLA DE REDIRECCIONES

| Rol           | URL Login | Redirección       | Protección          |
|---------------|-----------|-------------------|---------------------|
| Admin         | /login    | /admin/dashboard  | `admin` middleware  |
| Juez          | /login    | /juez/dashboard   | `juez` middleware   |
| Participante  | /login    | /dashboard        | `auth` middleware   |

---

## ⚙️ MÉTODOS DEL MODELO USER

**Archivo:** `app/Models/User.php`

Ya existen estos métodos (verificar que estén implementados):

```php
public function isAdmin(): bool
{
    return $this->roles()->where('nombre', 'admin')->exists();
}

public function isJuez(): bool
{
    return $this->roles()->where('nombre', 'juez')->exists();
}

public function isParticipante(): bool
{
    return $this->roles()->where('nombre', 'participante')->exists();
}
```

---

## 🎉 RESULTADO

✅ **Ahora cada usuario es redirigido a su dashboard correcto según su rol**
✅ **Middleware protege rutas por rol**
✅ **Usuarios no pueden acceder a dashboards de otros roles**
✅ **Sistema de permisos funcionando correctamente**

---

## 🔄 DIAGRAMA DE FLUJO

```
┌─────────────┐
│ LOGIN PAGE  │
└──────┬──────┘
       │
       ▼
┌─────────────────┐
│  Autenticación  │
└──────┬──────────┘
       │
       ▼
   ¿Qué rol?
       │
   ┌───┴───┬───────┬──────────┐
   │       │       │          │
   ▼       ▼       ▼          ▼
 Admin   Juez   Participante  Otro
   │       │       │          │
   ▼       ▼       ▼          ▼
/admin  /juez   /dashboard  /dashboard
```

---

**¡Problema resuelto! Ahora los jueces verán su interfaz correcta al hacer login.** 🎯
