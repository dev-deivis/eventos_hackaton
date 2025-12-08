# 📧 GUÍA COMPLETA DE CONFIGURACIÓN DE BREVO (SMTP)

## 🎯 PASO 1: OBTENER CREDENCIALES DE BREVO

### 1.1 Crear cuenta en Brevo
1. Ve a https://www.brevo.com/
2. Registra una cuenta gratuita
3. Verifica tu email

### 1.2 Obtener API Key SMTP
1. Inicia sesión en Brevo
2. Ve a **Settings** (Configuración) → **SMTP & API**
3. En la sección **SMTP**, haz clic en **Create SMTP Key**
4. Copia la clave generada (ejemplo: `xsmtpsib-a1b2c3d4...`)

### 1.3 Configurar dominio de envío (Opcional pero recomendado)
1. Ve a **Senders & IP** → **Senders**
2. Agrega tu email: `noreply@tudominio.com`
3. Verifica el email haciendo clic en el enlace que te enviarán

---

## 🔧 PASO 2: CONFIGURAR VARIABLES DE ENTORNO

### 2.1 Archivo `.env` (Desarrollo Local)

```env
# CONFIGURACIÓN DE CORREOS CON BREVO
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_email_verificado@ejemplo.com
MAIL_PASSWORD=xsmtpsib-tu_clave_smtp_aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ IMPORTANTE:**
- `MAIL_USERNAME`: Tu email verificado en Brevo
- `MAIL_PASSWORD`: La clave SMTP que generaste (NO tu contraseña de Brevo)
- `MAIL_FROM_ADDRESS`: Email desde el que se enviarán los correos

---

## 🚀 PASO 3: CONFIGURAR EN RAILWAY (PRODUCCIÓN)

### 3.1 Variables de entorno en Railway

1. Ve a tu proyecto en Railway
2. Abre la pestaña **Variables**
3. Agrega estas variables:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@ejemplo.com
MAIL_PASSWORD=xsmtpsib-tu_clave_smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME=Hackathon Events
```

4. Haz clic en **Deploy** para aplicar cambios

---

## 🧪 PASO 4: PROBAR ENVÍO DE CORREOS

### 4.1 Comando de prueba en Laravel

```bash
# Desde la raíz del proyecto
php artisan tinker
```

Ejecuta en Tinker:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoEventoMail;
use App\Models\Evento;

$evento = Evento::first();
Mail::to('tu_email_prueba@gmail.com')->send(new NuevoEventoMail($evento));
```

### 4.2 Script de prueba rápida

Crea archivo `test-email.php` en la raíz:

```php
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\Evento;
use App\Mail\NuevoEventoMail;

try {
    $evento = Evento::first();
    
    if (!$evento) {
        echo "❌ No hay eventos en la base de datos\n";
        exit(1);
    }
    
    Mail::to('prueba@tudominio.com')->send(new NuevoEventoMail($evento));
    
    echo "✅ ¡Correo enviado exitosamente!\n";
    echo "📧 Revisa tu bandeja de entrada\n";
    
} catch (\Exception $e) {
    echo "❌ Error al enviar correo:\n";
    echo $e->getMessage() . "\n";
}
```

Ejecuta:
```bash
php test-email.php
```

---

## 📝 PASO 5: HABILITAR CORREOS EN EL CÓDIGO

### 5.1 Actualizar NotificacionHelper.php

Busca el archivo `app/Helpers/NotificacionHelper.php` y encuentra estas líneas:

```php
// ⚠️ CORREOS DESHABILITADOS TEMPORALMENTE
// Mail::to($usuario->email)->send(new SolicitudEquipoMail($equipo, $solicitante));
```

Descomenta para habilitar:

```php
// ✅ CORREOS HABILITADOS
Mail::to($usuario->email)->send(new SolicitudEquipoMail($equipo, $solicitante));
```

### 5.2 Ubicaciones donde habilitar correos

Busca en el proyecto el texto `CORREOS DESHABILITADOS` y descomenta:

**Archivos a revisar:**
1. `app/Helpers/NotificacionHelper.php`
2. `app/Http/Controllers/EquipoController.php`
3. `app/Http/Controllers/EventoController.php`
4. `app/Http/Controllers/JuezController.php`
5. `app/Http/Controllers/AdminController.php`

---

## 🔍 PASO 6: VERIFICAR CONFIGURACIÓN

### 6.1 Comando artisan

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### 6.2 Ver configuración actual

```bash
php artisan tinker
```

```php
config('mail.from');
config('mail.mailers.smtp');
```

---

## 📊 PASO 7: LÍMITES DE BREVO (PLAN GRATUITO)

### Plan Gratuito:
- ✅ 300 correos por día
- ✅ SMTP ilimitado (pero limitado por día)
- ✅ Plantillas HTML
- ❌ Sin soporte prioritario

### Recomendaciones:
- Para desarrollo: Plan gratuito es suficiente
- Para producción: Considera plan de pago si necesitas >300 correos/día
- Monitorea tu uso en el dashboard de Brevo

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "Connection refused"
**Causa:** Firewall o puerto bloqueado
**Solución:** 
- Verifica que el puerto 587 esté abierto
- Prueba con puerto 465 (SSL): `MAIL_PORT=465` y `MAIL_ENCRYPTION=ssl`

### Error: "Authentication failed"
**Causa:** Credenciales incorrectas
**Solución:**
- Verifica que `MAIL_PASSWORD` sea la clave SMTP (no tu contraseña)
- Regenera la clave SMTP en Brevo si es necesario

### Error: "Sender not verified"
**Causa:** Email no verificado en Brevo
**Solución:**
- Ve a Brevo → Senders
- Verifica tu email haciendo clic en el enlace

### Los correos llegan a SPAM
**Solución:**
- Configura SPF, DKIM y DMARC en tu dominio
- Usa un dominio verificado (no @gmail.com)
- Evita palabras spam en el asunto

---

## 📋 CHECKLIST FINAL

- [ ] Cuenta Brevo creada y verificada
- [ ] Clave SMTP generada
- [ ] Email verificado en Brevo
- [ ] Variables .env configuradas localmente
- [ ] Variables configuradas en Railway
- [ ] Correo de prueba enviado exitosamente
- [ ] Código descomentado y habilitado
- [ ] Cache limpiado
- [ ] Probado en producción

---

## 💡 TIPS ADICIONALES

### Usar Cola de Trabajos (Queue)
Para no bloquear las peticiones:

```php
// En lugar de:
Mail::to($user->email)->send(new SolicitudEquipoMail($equipo, $user));

// Usa:
Mail::to($user->email)->queue(new SolicitudEquipoMail($equipo, $user));
```

Configura en `.env`:
```env
QUEUE_CONNECTION=database
```

Ejecuta el worker:
```bash
php artisan queue:work
```

### Logs de correos
Ver errores en:
```bash
tail -f storage/logs/laravel.log
```

### Testing sin enviar correos reales
En `.env.testing`:
```env
MAIL_MAILER=array
```

---

## 🎉 ¡LISTO!

Ahora tu sistema de correos está completamente configurado y funcional.

**Próximos pasos:**
1. Prueba cada tipo de correo
2. Personaliza las plantillas según tu marca
3. Configura queue para mejor rendimiento
4. Monitorea el uso en Brevo

---

**Documentado por:** Claude AI
**Fecha:** Diciembre 2024
**Versión:** 1.0
