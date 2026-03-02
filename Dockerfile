# ===========================================
# Stage 1: Build Node/Vite assets
# ===========================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json .npmrc ./

# Install dependencies
RUN npm ci

# Copy source and configs
COPY vite.config.ts tsconfig.json eslint.config.js jsconfig.json postcss.config.js tailwind.config.js ./
COPY resources/ ./resources/

# Build Vite assets (creates public/build/manifest.json)
RUN npm run build

# ===========================================
# Stage 2: PHP-FPM runtime
# ===========================================
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
    supervisor

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

# Copy application source (without node_modules, vendor built in stage)
COPY --chown=www-data:www-data . .

# Remove node files not needed in runtime
RUN rm -rf node_modules

# Copy Vite build output from node stage
COPY --from=node-builder --chown=www-data:www-data /app/public/build /var/www/html/public/build

# ----------------------------
# Permissions
# ----------------------------
ENV TMPDIR=/tmp
RUN chmod 1777 /tmp \
    && mkdir -p storage bootstrap/cache storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 775 public/build

# ----------------------------
# Install PHP dependencies (no dev)
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
# Start: cache + migrate + run supervisor
# ----------------------------
CMD ["sh", "-lc", "\
php artisan config:cache || true && \
php artisan route:cache || true && \
php artisan view:cache || true && \
php artisan migrate --force || true && \
supervisord -c /etc/supervisor/conf.d/supervisord.conf \
"]
