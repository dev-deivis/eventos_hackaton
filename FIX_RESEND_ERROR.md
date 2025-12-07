# 🔧 FIX COMPLETO: Error "Class Resend not found"

## ✅ PROBLEMA SOLUCIONADO

**Causa del error:**
- Tu código en `routes/web.php` intentaba acceder a `config('services.resend.key')`
- Esto hace que Laravel busque el driver de Resend
- Pero NO tienes instalado el paquete de Resend
- Y estás usando Brevo (SMTP) que SÍ funciona

**Solución aplicada:**
- ✅ Actualicé `routes/web.php` para usar configuración SMTP
- ✅ Actualicé `config/services.php` eliminando referencia a Resend
- ✅ Ahora el código usa SOLO SMTP (compatible con Brevo)

---

## 📋 PASOS PARA DESPLEGAR

### OPCIÓN A: Deploy Automático

```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"
.\fix-deploy-brevo.bat
```

### OPCIÓN B: Deploy Manual

```bash
cd "C:\Users\LENOVO\Documents\7MO SEMESTRE\WEB\hackathon-events"
git add routes/web.php config/services.php
git commit -m "Fix: Cambiar de Resend a Brevo SMTP"
git push origin main
```

---

## 🔑 VARIABLES EN RAILWAY (Verifica que tengas estas)

Ve a Railway → Tu proyecto → Variables:

```env
# CONFIGURACIÓN MAIL
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=alonsoalmaraz18@gmail.com
MAIL_PASSWORD=[tu_smtp_key_de_brevo]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=alonsoalmaraz18@gmail.com
MAIL_FROM_NAME=Hackathon Events
```

**🚨 IMPORTANTE:** 
- Si NO tienes `MAIL_PASSWORD` (la SMTP key de Brevo), ve a Brevo y genera una
- La SMTP key NO es tu contraseña de Gmail, es una key especial de Brevo

---

## 🧪 PROBAR DESPUÉS DEL DEPLOY

1. **Espera 2-3 minutos** que Railway termine de desplegar
2. Ve a: https://web-production-ef44a.up.railway.app/test-email
3. Deberías ver: **✅ Correo enviado!**
4. Revisa tu email: `alonsoalmaraz18@gmail.com`

---

## ❓ SI AÚN NO TIENES LA SMTP KEY DE BREVO

### Paso 1: Login en Brevo
1. Ve a: https://app.brevo.com/
2. Login con `alonsoalmaraz18@gmail.com`

### Paso 2: Generar SMTP Key
1. Click en tu nombre (arriba derecha)
2. Settings → SMTP & API
3. Tab "SMTP"
4. Click "Generate a new SMTP key"
5. Dale un nombre: "Railway Hackathon"
6. **COPIA LA KEY** (se ve algo así: `xsmtpsib-abc123def456...`)

### Paso 3: Agregar en Railway
1. Ve a Railway → Variables
2. Busca `MAIL_PASSWORD`
3. Pega la key que copiaste
4. Guarda

---

## 📊 DIFERENCIAS ENTRE EL ANTES Y DESPUÉS

### ANTES (Con Resend - NO funcionaba)
```php
'RESEND_API_KEY' => config('services.resend.key'), // ❌ Buscaba clase Resend
```

### DESPUÉS (Con SMTP/Brevo - SÍ funciona)
```php
'MAIL_HOST' => config('mail.mailers.smtp.host'),      // ✅ Usa SMTP estándar
'MAIL_PORT' => config('mail.mailers.smtp.port'),
'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
```

---

## 🎯 RESUMEN

| Acción | Status |
|--------|--------|
| Código actualizado | ✅ |
| Config actualizada | ✅ |
| Listo para deploy | ✅ |
| Variables en Railway | 🔍 Verifica |
| SMTP key de Brevo | 🔍 Verifica |

**Siguiente paso:**
1. Ejecuta `.\fix-deploy-brevo.bat` O haz push manual
2. Espera 2-3 min
3. Prueba `/test-email`
4. ¡Debería funcionar! ✅

---

## 💡 NOTAS IMPORTANTES

- **NO necesitas instalar nada** (ni Resend ni nada)
- **SMTP funciona out-of-the-box** en Laravel
- **Brevo da 300 emails/día gratis** (suficiente para tu proyecto)
- El código ahora es **compatible con cualquier proveedor SMTP**

Si después del deploy aún hay error, compárteme el mensaje de error exacto.
