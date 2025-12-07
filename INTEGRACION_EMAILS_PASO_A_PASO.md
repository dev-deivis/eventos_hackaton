# 🔧 INTEGRACIÓN DE EMAILS EN CONTROLADORES

## 📧 1. REGISTRO DE USUARIO

### Archivo: `app/Http/Controllers/Auth/RegisteredUserController.php`

Agregar al inicio del archivo (después de los otros `use`):

```php
use App\Mail\BienvenidaMail;
use Illuminate\Support\Facades\Mail;
```

Agregar DESPUÉS de crear el participante y ANTES de `event(new Registered($user));`:

```php
// Enviar email de bienvenida
try {
    Mail::to($user->email)->send(new BienvenidaMail($user));
} catch (\Exception $e) {
    \Log::error('Error enviando email de bienvenida: ' . $e->getMessage());
}
```

El código completo en el método `store()` quedaría así:

```php
// Crear perfil de participante
Participante::create([
    'user_id' => $user->id,
    'carrera_id' => $request->carrera_id,
    'no_control' => $request->no_control,
    'semestre' => $request->semestre,
    'telefono' => $request->telefono,
    'biografia' => 'Estudiante apasionado por la tecnología y la innovación.',
]);

// 📧 ENVIAR EMAIL DE BIENVENIDA
try {
    Mail::to($user->email)->send(new BienvenidaMail($user));
} catch (\Exception $e) {
    \Log::error('Error enviando email de bienvenida: ' . $e->getMessage());
}

event(new Registered($user));
Auth::login($user);
return redirect(route('dashboard', absolute: false));
```

---

## 📧 2. CREAR EVENTO

### Archivo: `app/Http/Controllers/EventoController.php` o `AdminController.php`

Buscar el método donde se crea un evento (probablemente `store()`).

Agregar al inicio:

```php
use App\Mail\NuevoEventoMail;
use Illuminate\Support\Facades\Mail;
```

Agregar DESPUÉS de crear el evento:

```php
// 📧 ENVIAR NOTIFICACIÓN A TODOS LOS PARTICIPANTES
try {
    // Obtener todos los usuarios con rol participante
    $participantes = \App\Models\User::whereHas('roles', function($query) {
        $query->where('nombre', 'participante');
    })->get();

    // Enviar email a cada participante (en segundo plano si tienes queue)
    foreach ($participantes as $participante) {
        Mail::to($participante->email)->send(new NuevoEventoMail($evento, $participante));
    }
    
    \Log::info('Emails de nuevo evento enviados a ' . $participantes->count() . ' participantes');
} catch (\Exception $e) {
    \Log::error('Error enviando emails de nuevo evento: ' . $e->getMessage());
}
```

---

## 📧 3. SOLICITUD DE UNIÓN A EQUIPO

### Archivo: `app/Http/Controllers/EquipoController.php`

Buscar el método donde se solicita unirse (probablemente `solicitarUnirse()` o similar).

Agregar al inicio:

```php
use App\Mail\SolicitudUnionEquipoMail;
use Illuminate\Support\Facades\Mail;
```

Agregar DESPUÉS de agregar la solicitud al equipo:

```php
// 📧 ENVIAR EMAIL AL LÍDER DEL EQUIPO
try {
    $lider = $equipo->lider->user;
    $solicitante = auth()->user();
    
    Mail::to($lider->email)->send(
        new SolicitudUnionEquipoMail($equipo, $solicitante, $lider)
    );
} catch (\Exception $e) {
    \Log::error('Error enviando email de solicitud de unión: ' . $e->getMessage());
}
```

---

## 📧 4. ACEPTACIÓN EN EQUIPO

### Archivo: `app/Http/Controllers/EquipoController.php`

Buscar el método donde se acepta un miembro (probablemente `aceptarMiembro()` o similar).

Agregar al inicio (si no está):

```php
use App\Mail\AceptadoEnEquipoMail;
use Illuminate\Support\Facades\Mail;
```

Agregar DESPUÉS de actualizar el estado a 'aceptado':

```php
// 📧 ENVIAR EMAIL AL PARTICIPANTE ACEPTADO
try {
    $participante = \App\Models\Participante::findOrFail($participanteId);
    $user = $participante->user;
    
    Mail::to($user->email)->send(new AceptadoEnEquipoMail($equipo, $user));
} catch (\Exception $e) {
    \Log::error('Error enviando email de aceptación: ' . $e->getMessage());
}
```

---

## 🧪 PROBAR LOCALMENTE

1. Limpia el caché:
```bash
php artisan config:clear
php artisan cache:clear
```

2. Prueba con artisan tinker:
```bash
php artisan tinker
```

```php
// Probar email de bienvenida
$user = \App\Models\User::first();
Mail::to('tu-email@test.com')->send(new \App\Mail\BienvenidaMail($user));

// Ver si hay errores
echo "Email enviado!";
```

---

## 📝 ARCHIVOS A MODIFICAR

1. ✅ `app/Http/Controllers/Auth/RegisteredUserController.php`
2. ✅ `app/Http/Controllers/EventoController.php` (o AdminController.php)
3. ✅ `app/Http/Controllers/EquipoController.php`

---

## ⚙️ CONFIGURACIÓN EN RENDER

### Variables de Entorno a Agregar:

Ve a tu proyecto en Render → Environment → Add Environment Variable

```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=eventos.contacto.web@gmail.com
MAIL_PASSWORD=lxxx gyrq bgrn ubty
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=eventos.contacto.web@gmail.com
MAIL_FROM_NAME=Eventos Hackathon TecNM
```

**IMPORTANTE:** Después de agregar las variables, haz click en "Save Changes" y Render redesplegará automáticamente.

---

## 🔄 CONFIGURAR BREVO

### Paso 1: Crear Cuenta en Brevo

1. Ve a [https://www.brevo.com](https://www.brevo.com)
2. Crea una cuenta gratuita
3. Verifica tu email

### Paso 2: Configurar SMTP

1. Ve a **Settings** → **SMTP & API**
2. Busca la sección **SMTP**
3. Verás:
   - **Host:** `smtp-relay.brevo.com`
   - **Port:** `587`
   - **Username:** (tu email de Brevo)
   - **Password:** (generar nueva clave SMTP)

### Paso 3: Generar Clave SMTP

1. Click en "Create a new SMTP key"
2. Dale un nombre: "Laravel Eventos"
3. Copia la clave generada
4. Úsala en `MAIL_PASSWORD`

### Paso 4: Verificar Remitente

1. Ve a **Settings** → **Senders & IP**
2. Click en "Add a sender"
3. Agrega: `eventos.contacto.web@gmail.com`
4. Verifica el email (Brevo te enviará un email de confirmación)

---

## ✅ CHECKLIST FINAL

- [ ] Archivos Mailable creados (ya está ✅)
- [ ] Vistas de emails creadas (ya está ✅)
- [ ] `.env` local actualizado (ya está ✅)
- [ ] Código agregado en RegisteredUserController
- [ ] Código agregado en EventoController
- [ ] Código agregado en EquipoController (solicitud)
- [ ] Código agregado en EquipoController (aceptación)
- [ ] Probado localmente
- [ ] Cuenta creada en Brevo
- [ ] Remitente verificado en Brevo
- [ ] Variables configuradas en Render
- [ ] Desplegado en Render
- [ ] Probado en producción

---

¿Necesitas ayuda para implementar esto? ¡Dime qué parte quieres que hagamos primero!
