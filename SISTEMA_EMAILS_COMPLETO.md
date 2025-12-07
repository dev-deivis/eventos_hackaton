# 📧 SISTEMA DE NOTIFICACIONES POR EMAIL - GUÍA COMPLETA

## ✅ ARCHIVOS CREADOS

### Backend (Mailables):
1. ✅ `app/Mail/BienvenidaMail.php` - Email de registro
2. ✅ `app/Mail/NuevoEventoMail.php` - Notificación de nuevo evento
3. ✅ `app/Mail/SolicitudUnionEquipoMail.php` - Notificación al líder
4. ✅ `app/Mail/AceptadoEnEquipoMail.php` - Confirmación de aceptación

### Frontend (Vistas):
1. ✅ `resources/views/emails/bienvenida.blade.php`
2. ✅ `resources/views/emails/nuevo-evento.blade.php`
3. ✅ `resources/views/emails/solicitud-union-equipo.blade.php`
4. ✅ `resources/views/emails/aceptado-en-equipo.blade.php`

### Configuración:
1. ✅ `.env` actualizado con credenciales de Brevo

---

## 🔧 CONFIGURACIÓN REALIZADA EN .ENV

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=eventos.contacto.web@gmail.com
MAIL_PASSWORD="lxxx gyrq bgrn ubty"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="eventos.contacto.web@gmail.com"
MAIL_FROM_NAME="Eventos Hackathon TecNM"
```

---

## 📝 CÓMO INTEGRAR EN LOS CONTROLADORES

### 1. Email de Bienvenida (Al Registrarse)

**Archivo:** `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
use App\Mail\BienvenidaMail;
use Illuminate\Support\Facades\Mail;

// En el método store(), después de crear el usuario:
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    // 📧 ENVIAR EMAIL DE BIENVENIDA
    try {
        Mail::to($user->email)->send(new BienvenidaMail($user));
    } catch (\Exception $e) {
        \Log::error('Error enviando email de bienvenida: ' . $e->getMessage());
    }

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
```

---

### 2. Email de Nuevo Evento (Al Crear Evento)

**Archivo:** `app/Http/Controllers/EventoController.php`

```php
use App\Mail\NuevoEventoMail;
use Illuminate\Support\Facades\Mail;

// En el método store(), después de crear el evento:
public function store(Request $request)
{
    // ... validación y creación del evento ...
    
    $evento = Evento::create($validated);

    // 📧 ENVIAR EMAIL A TODOS LOS PARTICIPANTES
    try {
        // Obtener todos los usuarios con rol participante
        $participantes = \App\Models\User::whereHas('roles', function($query) {
            $query->where('nombre', 'participante');
        })->get();

        // Enviar email a cada participante
        foreach ($participantes as $participante) {
            Mail::to($participante->email)->send(new NuevoEventoMail($evento, $participante));
        }
    } catch (\Exception $e) {
        \Log::error('Error enviando emails de nuevo evento: ' . $e->getMessage());
    }

    return redirect()->route('admin.eventos.index')
        ->with('success', 'Evento creado y notificaciones enviadas.');
}
```

---

### 3. Email de Solicitud de Unión (Al Solicitar Unirse)

**Archivo:** `app/Http/Controllers/EquipoController.php`

```php
use App\Mail\SolicitudUnionEquipoMail;
use Illuminate\Support\Facades\Mail;

// En el método solicitarUnirse():
public function solicitarUnirse(Request $request, $equipoId)
{
    $equipo = Equipo::findOrFail($equipoId);
    $participante = auth()->user()->participante;

    // ... validaciones ...

    // Agregar al equipo con estado pendiente
    $equipo->participantes()->attach($participante->id, [
        'estado' => 'pendiente',
        'fecha_union' => now(),
    ]);

    // 📧 ENVIAR EMAIL AL LÍDER DEL EQUIPO
    try {
        $lider = $equipo->lider->user;
        $solicitante = auth()->user();
        
        Mail::to($lider->email)->send(
            new SolicitudUnionEquipoMail($equipo, $solicitante, $lider)
        );
    } catch (\Exception $e) {
        \Log::error('Error enviando email de solicitud: ' . $e->getMessage());
    }

    return redirect()->back()
        ->with('success', 'Solicitud enviada. El líder del equipo recibirá una notificación.');
}
```

---

### 4. Email de Aceptación (Al Aceptar Miembro)

**Archivo:** `app/Http/Controllers/EquipoController.php`

```php
use App\Mail\AceptadoEnEquipoMail;
use Illuminate\Support\Facades\Mail;

// En el método aceptarMiembro():
public function aceptarMiembro(Request $request, $equipoId, $participanteId)
{
    $equipo = Equipo::findOrFail($equipoId);
    
    // ... validaciones ...

    // Actualizar estado a aceptado
    $equipo->participantes()->updateExistingPivot($participanteId, [
        'estado' => 'aceptado',
    ]);

    // 📧 ENVIAR EMAIL AL PARTICIPANTE ACEPTADO
    try {
        $participante = \App\Models\Participante::findOrFail($participanteId);
        $user = $participante->user;
        
        Mail::to($user->email)->send(new AceptadoEnEquipoMail($equipo, $user));
    } catch (\Exception $e) {
        \Log::error('Error enviando email de aceptación: ' . $e->getMessage());
    }

    return redirect()->back()
        ->with('success', 'Miembro aceptado. Se le ha enviado una notificación por correo.');
}
```

---

## 🚀 CONFIGURACIÓN PARA RENDER

### Paso 1: Variables de Entorno en Render

En el dashboard de Render, agrega estas variables:

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

### Paso 2: Configurar Queue (Opcional pero Recomendado)

Los emails se envían mejor en segundo plano. En Render:

1. Agrega a tu `.env` en Render:
```
QUEUE_CONNECTION=database
```

2. En tu `render.yaml` o script de deploy, agrega:
```bash
php artisan queue:work --daemon
```

---

## 🧪 PRUEBAS LOCALES

### Probar envío de email:

```bash
php artisan tinker
```

```php
// Probar email de bienvenida
$user = \App\Models\User::first();
Mail::to($user->email)->send(new \App\Mail\BienvenidaMail($user));

// Probar email de nuevo evento
$evento = \App\Models\Evento::first();
Mail::to($user->email)->send(new \App\Mail\NuevoEventoMail($evento, $user));
```

---

## 📊 CONFIGURACIÓN DE BREVO

### Pasos en Brevo (brevo.com):

1. **Crear cuenta en Brevo** (si no tienes)
2. **Ir a:** Settings → SMTP & API
3. **Obtener credenciales SMTP:**
   - Host: `smtp-relay.brevo.com`
   - Port: `587`
   - Username: `tu-email@gmail.com`
   - Password: (generar nueva si es necesario)

4. **Verificar dominio** (opcional pero recomendado):
   - Ir a Senders → Domains
   - Agregar tu dominio
   - Configurar registros DNS

---

## ⚙️ MEJORAS OPCIONALES

### 1. Usar Queue para envíos masivos

```bash
php artisan queue:table
php artisan migrate
```

Luego en el código:
```php
Mail::to($user->email)->queue(new BienvenidaMail($user));
```

### 2. Logs de emails enviados

Crear modelo `EmailLog`:
```php
EmailLog::create([
    'user_id' => $user->id,
    'tipo' => 'bienvenida',
    'destinatario' => $user->email,
    'enviado_at' => now(),
]);
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Connection refused"
- Verifica las credenciales en `.env`
- Verifica que Brevo está activo
- Prueba con `php artisan config:clear`

### Error: "Authentication failed"
- Genera nueva contraseña SMTP en Brevo
- Verifica que usas `smtp-relay.brevo.com`

### Emails no llegan
- Revisa spam
- Verifica el dominio del remitente
- Revisa logs: `tail -f storage/logs/laravel.log`

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [ ] `.env` actualizado con credenciales Brevo
- [ ] Archivos Mailable creados
- [ ] Vistas de emails creadas
- [ ] Integrado en RegisteredUserController
- [ ] Integrado en EventoController
- [ ] Integrado en EquipoController (solicitud)
- [ ] Integrado en EquipoController (aceptación)
- [ ] Probado localmente
- [ ] Variables configuradas en Render
- [ ] Probado en producción

---

## 📝 SIGUIENTE PASO

Ahora voy a crear los scripts de integración en los controladores...
