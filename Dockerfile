# ---------- Laravel (PHP-FPM) + Nginx (Alpine) ----------
FROM php:8.2-fpm-alpine

# ----------------------------
# System dependencies
# ----------------------------
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

# ----------------------------
# PHP extensions
# ----------------------------
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    mbstring \
    zip \
    opcache

# ----------------------------
# Composer
# ----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------
# App directory
# ----------------------------
WORKDIR /var/www/html
COPY . .

# ----------------------------
# Ensure temp dir + storage perms (fix tempnam() / cache issues)
# ----------------------------
ENV TMPDIR=/tmp
RUN chmod 1777 /tmp \
 && mkdir -p storage bootstrap/cache storage/framework/{cache,sessions,views} \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 775 storage bootstrap/cache

# ----------------------------
# Install Node deps & build Vite assets (creates public/build/manifest.json)
# ----------------------------
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi \
 && npm run build

# ----------------------------
# Install PHP deps (no dev)
# ----------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ----------------------------
# Nginx config
# ----------------------------
RUN rm -f /etc/nginx/http.d/default.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# ----------------------------
# Supervisor config
# ----------------------------
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Railway provides $PORT; your Nginx should listen on 8080 internally
EXPOSE 8080

# ----------------------------
# Start: cache (safe) + migrate (safe) + run supervisor
# ----------------------------
CMD ["sh", "-lc", "\
php artisan config:cache || true && \
php artisan route:cache || true && \
php artisan view:cache || true && \
php artisan migrate --force || true && \
supervisord -c /etc/supervisor/conf.d/supervisord.conf \
"]