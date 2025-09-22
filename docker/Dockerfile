# docker/Dockerfile
FROM dunglas/frankenphp:latest

# Set working dir
WORKDIR /app

# Dependensi PHP & ekstensi
RUN apt-get update && apt-get install -y \
    unzip git libpq-dev libzip-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*
    

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /app

# Install prod dependencies (laravel/framework ^12)
RUN composer install --optimize-autoloader

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

 # Copy Caddyfile for FrankenPHP
COPY docker/Caddyfile /etc/caddy/Caddyfile

# Expose HTTP port
EXPOSE 8080

# FrankenPHP entrypoint
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]