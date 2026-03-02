# ---------- PHP + Nginx production image ----------
FROM php:8.2-fpm-alpine

# System deps
RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    supervisor

# PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    mbstring \
    zip \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App directory
WORKDIR /var/www/html

# Copy app
COPY . .

# Install PHP deps (no dev)
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Nginx config
RUN rm -f /etc/nginx/http.d/default.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port (Railway provides $PORT)
EXPOSE 8080

# Run migrations then start services
CMD ["sh", "-lc", "php artisan config:cache || true && php artisan route:cache || true && php artisan view:cache || true && php artisan migrate --force || true && supervisord -c /etc/supervisor/conf.d/supervisord.conf"]