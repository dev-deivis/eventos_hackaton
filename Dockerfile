FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar composer files primero (para aprovechar cache de Docker)
COPY composer.json composer.lock ./

# Instalar dependencias de PHP (sin scripts para evitar errores)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copiar package.json files
COPY package*.json ./

# Instalar dependencias de Node
RUN npm ci --prefer-offline --no-audit

# Copiar el resto del código
COPY . .

# Completar instalación de Composer
RUN composer dump-autoload --optimize --no-dev

# Crear directorios necesarios
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Dar permisos
RUN chmod -R 775 storage bootstrap/cache

# Compilar assets (con timeout)
RUN timeout 300 npm run build || echo "Build completed or timed out"

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080
