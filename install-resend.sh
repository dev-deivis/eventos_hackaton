#!/bin/bash

echo "🔧 Instalando Resend para Laravel..."

# Instalar el paquete de Resend
composer require resendlabs/resend-laravel

echo "✅ Resend instalado correctamente"
echo ""
echo "📝 Ahora configura estas variables en Railway:"
echo "   MAIL_MAILER=resend"
echo "   RESEND_API_KEY=tu_api_key_de_resend"
