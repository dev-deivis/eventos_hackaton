FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar archivos de dependencias
COPY composer.json composer.lock package.json package-lock.json ./

# Instalar dependencias PHP
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --optimize-autoloader

# Instalar dependencias Node
RUN npm ci

# Copiar código de la aplicación
COPY . .

# Completar instalación de Composer
RUN composer dump-autoload --optimize --no-dev

# Construir assets
RUN npm run build

# Crear directorios necesarios y permisos
RUN mkdir -p storage/framework/{cache/data,sessions,views} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Configurar Nginx
COPY <<EOF /etc/nginx/sites-available/default
server {
    listen 8080;
    server_name _;
    root /var/www/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Configurar Supervisor
COPY <<EOF /etc/supervisor/conf.d/supervisord.conf
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
EOF

# Script de inicio
COPY <<'EOF' /start.sh
#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Optimizar configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
EOF

RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
