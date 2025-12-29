# docker/Dockerfile
FROM dunglas/frankenphp:latest

# ===== PHP upload & post size config =====
RUN echo "post_max_size=20M" > /usr/local/etc/php/conf.d/99-uploads.ini \
 && echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/99-uploads.ini \
 && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/99-uploads.ini \
 && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/99-uploads.ini

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
# RUN composer install --optimize-autoloader
RUN composer install --ignore-platform-reqs

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

 # Copy Caddyfile for FrankenPHP
COPY docker/Caddyfile /etc/caddy/Caddyfile

# Expose HTTP port
EXPOSE 8080

# FrankenPHP entrypoint
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]