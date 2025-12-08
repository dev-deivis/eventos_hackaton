# 🔑 ERROR: Authentication Failed (535)

## ❌ El problema actual:
```
Failed to authenticate on SMTP server with username "alonsoalmaraz18@gmail.com"
Error code "535" - Authentication failed
```

Esto significa que **TUS CREDENCIALES DE BREVO ESTÁN INCORRECTAS O EXPIRADAS**.

---

## ✅ SOLUCIÓN: Regenerar API Key de Brevo

### 📋 Paso 1: Acceder a Brevo
1. Ve a: https://app.brevo.com/
2. Inicia sesión con `alonsoalmaraz18@gmail.com`

### 🔑 Paso 2: Generar nueva SMTP Key
1. Click en tu nombre (esquina superior derecha)
2. **"SMTP & API"** en el menú
3. **"SMTP"** en la pestaña
4. Click en **"Create a new SMTP key"**
5. Dale un nombre: `Laravel Hackathon Events`
6. Click **"Generate"**
7. **¡COPIA LA CLAVE INMEDIATAMENTE!** (solo se muestra una vez)

```
Formato de la clave:
xsmtpsib-XXXXXXXXXXXXXXXXXXXXX-YYYYYYYYYYYYYY
```

### ✉️ Paso 3: Verificar que tu email está autorizado
1. En Brevo, ve a **"Senders"** (Remitentes)
2. Busca `alonsoalmaraz18@gmail.com`
3. Si tiene ✅ verde → está verificado
4. Si tiene ⚠️ naranja → Click en "Verify" y revisa tu correo

**IMPORTANTE:** El email debe estar verificado para enviar correos.

---

## 📝 Paso 4: Actualizar tu .env

Abre el archivo `.env` y actualiza estas líneas:

```env
MAIL_USERNAME=alonsoalmaraz18@gmail.com
MAIL_PASSWORD=xsmtpsib-TU_NUEVA_CLAVE_AQUI
```

**⚠️ IMPORTANTE:**
- `MAIL_USERNAME` = Tu email de Brevo (verificado)
- `MAIL_PASSWORD` = La clave SMTP que generaste (NO tu contraseña de Gmail)

---

## 🧪 Paso 5: Probar

```bash
php artisan config:clear
php test-brevo-email.php
```

Deberías ver:
```
✅ Correo enviado exitosamente
```

---

## 🆘 Si aún no funciona:

### Verifica estos puntos:

#### 1. Email NO verificado
**Síntoma:** Mismo error 535  
**Solución:** Ve a Brevo → Senders → Verify email

#### 2. API Key incorrecta
**Síntoma:** Error 535  
**Solución:** Regenera la clave y cópiala bien (sin espacios)

#### 3. Usaste contraseña de Gmail en vez de API Key
**Síntoma:** Error 535  
**Solución:** `MAIL_PASSWORD` debe ser la clave que empieza con `xsmtpsib-`

#### 4. Cuenta Brevo suspendida
**Síntoma:** Error 535  
**Solución:** Revisa tu email, puede que Brevo te haya enviado una notificación

---

## 📸 CAPTURAS GUÍA

### Dónde está SMTP & API:
```
┌─────────────────────────────────────┐
│ Tu nombre (arriba derecha)          │
│   ↓                                 │
│   • Account Settings                │
│   • SMTP & API      ← AQUÍ          │
│   • Billing                         │
│   • Logout                          │
└─────────────────────────────────────┘
```

### Pestaña SMTP:
```
┌────────────────────────────────────────┐
│  [ SMTP ]  [ API ]                     │
│                                        │
│  Your SMTP credentials                 │
│  ┌──────────────────────────────────┐ │
│  │ alonsoalmaraz18@gmail.com         │ │
│  │ Port: 587                         │ │
│  │                                   │ │
│  │ [Create a new SMTP key] ← CLICK  │ │
│  └──────────────────────────────────┘ │
└────────────────────────────────────────┘
```

---

## 🎯 Resumen rápido:

```
1. https://app.brevo.com/ → Login
2. Tu nombre → SMTP & API → SMTP
3. "Create a new SMTP key"
4. Copiar clave (xsmtpsib-...)
5. Actualizar MAIL_PASSWORD en .env
6. php artisan config:clear
7. php test-brevo-email.php
8. ✅ ¡Funciona!
```

---

## 💡 Datos importantes de tu cuenta:

```
Email: alonsoalmaraz18@gmail.com
Host: smtp-relay.brevo.com
Puerto: 465 (SSL) ✅ Ya configurado
Encriptación: ssl ✅ Ya configurado
```

Solo falta la API Key correcta.

---

## 📞 Alternativa: Usar Gmail

Si Brevo no funciona, puedes usar Gmail temporalmente:

### Configurar Gmail:
1. https://myaccount.google.com/security
2. Activar "Verificación en 2 pasos"
3. Ir a "Contraseñas de aplicaciones"
4. Generar contraseña para "Correo"
5. Copiar la contraseña de 16 caracteres

### Actualizar .env:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=alonsoalmaraz18@gmail.com
MAIL_PASSWORD=la_contraseña_de_16_caracteres_de_google
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="alonsoalmaraz18@gmail.com"
MAIL_FROM_NAME="Hackathon Events"
```

```bash
php artisan config:clear
php test-brevo-email.php
```

**Límite:** 500 correos/día (suficiente para desarrollo)

---

**¡La clave está en regenerar la API Key de Brevo!** 🔑

Una vez que la tengas, actualiza el `.env` y funcionará perfectamente.
