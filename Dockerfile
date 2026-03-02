########################################
# Stage 1 — PHP + Node (Build assets)
########################################
FROM php:8.2-cli-alpine AS build

# System deps + Node (for Vite build)
RUN apk add --no-cache \
    nodejs npm \
    bash curl git unzip \
    icu-dev oniguruma-dev libzip-dev postgresql-dev

# PHP extensions (needed by Laravel)
RUN docker-php-ext-install \
    pdo pdo_pgsql intl mbstring zip

WORKDIR /app

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP deps (needed for artisan during build)
RUN composer install --no-dev --optimize-autoloader

# Install JS deps + build Vite (Wayfinder runs here, PHP exists ✅)
RUN npm ci && npm run build


########################################
# Stage 2 — PHP-FPM + Nginx Runtime
########################################
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

WORKDIR /var/www/html

# Copy app source
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Copy built Vite assets from build stage
COPY --from=build /app/public/build /var/www/html/public/build

# Laravel permissions
RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && chmod -R 755 public/build

# Nginx config
RUN rm -f /etc/nginx/http.d/default.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

CMD ["sh", "-lc", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force || true && supervisord -c /etc/supervisor/conf.d/supervisord.conf"]