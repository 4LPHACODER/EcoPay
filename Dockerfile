# ---------- PHP + Nginx production image ----------
FROM php:8.2-fpm-alpine

# System deps (add nodejs + npm)
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
    supervisor \
    nodejs \
    npm

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

# Install Node deps + build Vite assets (THIS CREATES public/build/manifest.json)
RUN npm ci || npm install
RUN npm run build

# Laravel permissions + required runtime dirs (fix tempnam / view cache issues)
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    /tmp \
 && chmod -R 775 storage bootstrap/cache /tmp

# Nginx config
RUN rm -f /etc/nginx/http.d/default.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port (Railway provides $PORT)
EXPOSE 8080

# Run migrations then start services
CMD ["sh", "-lc", "\
php artisan config:cache || true && \
php artisan route:cache || true && \
php artisan view:cache || true && \
php artisan migrate --force || true && \
supervisord -c /etc/supervisor/conf.d/supervisord.conf \
"]