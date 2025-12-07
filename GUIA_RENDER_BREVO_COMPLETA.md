# 🚀 GUÍA COMPLETA: RENDER + BREVO

## 📧 PARTE 1: CONFIGURAR BREVO

### Paso 1: Crear Cuenta en Brevo

1. **Ve a:** [https://www.brevo.com](https://www.brevo.com)
2. **Clic en:** "Sign up free"
3. **Completa:**
    - Email: `eventos.contacto.web@gmail.com` (o tu email personal)
    - Contraseña
    - Nombre de la empresa: "TecNM Eventos"
4. **Verifica tu email** (Brevo te enviará un email)

---

### Paso 2: Configurar SMTP en Brevo

1. **Inicia sesión en Brevo**
2. **Ve a:** Settings (⚙️ en la esquina superior derecha)
3. **Clic en:** "SMTP & API" (en el menú lateral)
4. **En la sección SMTP, verás:**
    ```
    SMTP server: smtp-relay.brevo.com
    Port: 587
    Login: tu-email@brevo.com
    ```

---

### Paso 3: Generar Clave SMTP

1. **En la misma página**, busca: "SMTP Keys"
2. **Clic en:** "Create a new SMTP key"
3. **Nombre de la clave:** `Laravel-Eventos-Hackathon`
4. **Clic en:** "Generate"
5. **¡COPIA LA CLAVE!** Se ve así: ``
6. **Guárdala** - la necesitarás para el `.env`

**⚠️ IMPORTANTE:** Esta clave solo se muestra UNA VEZ. Si la pierdes, tendrás que generar una nueva.

---

### Paso 4: Verificar Remitente

1. **Ve a:** Settings → **Senders & IP**
2. **Clic en:** "Add a sender"
3. **Completa:**
    - Email: `eventos.contacto.web@gmail.com`
    - From name: `Eventos Hackathon TecNM`
4. **Clic en:** "Add"
5. **Brevo enviará un email de verificación** a `eventos.contacto.web@gmail.com`
6. **Abre el email** y haz clic en el enlace de verificación

**✅ Listo!** Ahora Brevo puede enviar emails desde tu dirección.

---

### Paso 5: Plan Gratuito de Brevo

El plan gratuito incluye:

-   ✅ **300 emails por día**
-   ✅ SMTP ilimitado
-   ✅ Sin tarjeta de crédito

Para proyectos pequeños/medianos es más que suficiente.

---

## 🚀 PARTE 2: CONFIGURAR RENDER

### Paso 1: Acceder a tu Proyecto en Render

1. **Ve a:** [https://render.com](https://render.com)
2. **Inicia sesión**
3. **Selecciona tu proyecto** (eventos_hackaton)

---

### Paso 2: Agregar Variables de Entorno

1. **En tu proyecto**, ve a: **"Environment"** (pestaña)
2. **Clic en:** "Add Environment Variable"
3. **Agrega una por una:**

```
Key: MAIL_MAILER
Value: smtp
```

```
Key: MAIL_HOST
Value: smtp-relay.brevo.com
```

```
Key: MAIL_PORT
Value: 587
```

```
Key: MAIL_USERNAME
Value: eventos.contacto.web@gmail.com
```

```
Key: MAIL_PASSWORD
Value: [LA CLAVE QUE COPIASTE DE BREVO]
```

```
Key: MAIL_ENCRYPTION
Value: tls
```

```
Key: MAIL_FROM_ADDRESS
Value: eventos.contacto.web@gmail.com
```

```
Key: MAIL_FROM_NAME
Value: Eventos Hackathon TecNM
```

---

### Paso 3: Guardar y Redesplegar

1. **Después de agregar todas las variables**, clic en: **"Save Changes"**
2. **Render redesplegará automáticamente** tu aplicación
3. **Espera 3-5 minutos** hasta que termine el deploy

---

### Paso 4: Verificar Logs

1. **En Render**, ve a: **"Logs"** (pestaña)
2. **Busca:** `php artisan config:cache`
3. **Verifica que no haya errores** relacionados con email

---

## 🧪 PARTE 3: PROBAR EL SISTEMA

### Prueba 1: Localmente

```bash
php artisan tinker
```

```php
// Probar email de bienvenida
$user = \App\Models\User::first();
Mail::to('tu-email-personal@gmail.com')->send(new \App\Mail\BienvenidaMail($user));
exit
```

**¿Llegó el email?**

-   ✅ SI → Todo funciona
-   ❌ NO → Revisa spam o logs

---

### Prueba 2: Crear Usuario

1. **Ve a tu app** (local o Render)
2. **Registra un nuevo usuario**
3. **Revisa el email** que pusiste
4. **Deberías recibir** el email de bienvenida

---

### Prueba 3: Crear Evento (Admin)

1. **Inicia sesión como admin**
2. **Crea un nuevo evento**
3. **Todos los participantes** deberían recibir un email

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Problema 1: "Connection refused"

**Causa:** Credenciales incorrectas o Brevo no configurado

**Solución:**

1. Verifica las credenciales en `.env`
2. Verifica que la clave SMTP sea correcta
3. Ejecuta: `php artisan config:clear`

---

### Problema 2: "Authentication failed"

**Causa:** Contraseña SMTP incorrecta

**Solución:**

1. Ve a Brevo → Settings → SMTP & API
2. Genera una **nueva clave SMTP**
3. Actualiza `MAIL_PASSWORD` en `.env` y Render

---

### Problema 3: Emails no llegan

**Posibles causas:**

1. **Email en spam:**

    - Revisa la carpeta de spam
    - Marca el email como "No es spam"

2. **Remitente no verificado:**

    - Ve a Brevo → Senders & IP
    - Verifica que el email esté marcado con ✅

3. **Límite diario alcanzado:**

    - Plan gratuito: 300 emails/día
    - Ve a Brevo → Statistics para verificar

4. **Errores en logs:**
    ```bash
    tail -f storage/logs/laravel.log
    ```

---

### Problema 4: En Render no funciona, pero local sí

**Solución:**

1. Verifica que **TODAS** las variables estén en Render
2. Haz un nuevo deploy:
    ```
    Settings → Manual Deploy
    ```
3. Revisa los logs de Render

---

## 📊 MONITOREO EN BREVO

### Ver Estadísticas

1. **Ve a:** Statistics (en Brevo)
2. **Verás:**
    - Emails enviados
    - Emails entregados
    - Emails rebotados
    - Tasa de apertura

### Ver Emails Enviados

1. **Ve a:** Campaigns → Transactional
2. **Verás la lista** de todos los emails enviados

---

## 🔐 SEGURIDAD

### Buenas Prácticas:

1. **Nunca subas `.env` a Git:**

    ```bash
    # Ya está en .gitignore
    ```

2. **Usa variables de entorno en Render:**

    - No pongas credenciales en el código

3. **Regenera claves SMTP periódicamente:**

    - Cada 3-6 meses

4. **Monitorea el uso:**
    - Revisa Brevo para detectar actividad sospechosa

---

## 📈 MEJORAS FUTURAS

### 1. Usar Queue (Recomendado)

Para envíos masivos, usa colas:

```bash
php artisan queue:table
php artisan migrate
```

En `.env` y Render:

```
QUEUE_CONNECTION=database
```

En el código:

```php
Mail::to($user->email)->queue(new BienvenidaMail($user));
```

### 2. Templates Avanzados en Brevo

Brevo tiene editor visual de emails:

1. Ve a: Campaigns → Templates
2. Crea templates profesionales
3. Úsalos desde Laravel

### 3. Analytics

Brevo ofrece:

-   Tracking de apertura
-   Tracking de clicks
-   A/B testing

---

## ✅ CHECKLIST FINAL

### En Brevo:

-   [ ] Cuenta creada
-   [ ] Clave SMTP generada
-   [ ] Remitente verificado
-   [ ] Plan gratuito activado

### En Laravel (Local):

-   [ ] `.env` actualizado
-   [ ] Mailables creados
-   [ ] Vistas de emails creadas
-   [ ] Código integrado en controladores
-   [ ] Probado con tinker
-   [ ] Emails llegan correctamente

### En Render:

-   [ ] Variables de entorno agregadas
-   [ ] Desplegado exitosamente
-   [ ] Logs sin errores
-   [ ] Emails funcionan en producción

---

## 🎓 RECURSOS ADICIONALES

-   **Documentación Brevo:** https://developers.brevo.com/
-   **Documentación Laravel Mail:** https://laravel.com/docs/mail
-   **Render Docs:** https://render.com/docs

---

¿Tienes alguna duda? ¡Pregunta!
