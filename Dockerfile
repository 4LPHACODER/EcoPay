########################################
# Stage 1 — Node + PHP (Build assets)
########################################
FROM node:20-alpine AS node-build

# Install PHP in build stage (needed for wayfinder)
RUN apk add --no-cache php82 php82-cli php82-mbstring php82-openssl php82-pdo php82-tokenizer php82-json php82-phar php82-fileinfo

WORKDIR /app

# Copy package files first (for caching)
COPY package*.json ./
RUN npm ci

# Copy the rest of the app
COPY . .

# Install Composer dependencies (needed for artisan during build)
RUN apk add --no-cache curl git unzip \
 && curl -sS https://getcomposer.org/installer | php82 -- --install-dir=/usr/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader

# Build Vite assets (this now works because PHP exists)
RUN npm run build


########################################
# Stage 2 — PHP + Nginx Runtime
########################################
FROM php:8.2-fpm-alpine

# System dependencies
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

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Copy built assets from Node stage
COPY --from=node-build /app/public/build /var/www/html/public/build

# Permissions
RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && chmod -R 755 public/build

# Remove default nginx config
RUN rm -f /etc/nginx/http.d/default.conf

# Copy your configs
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

CMD ["sh", "-lc", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force || true && supervisord -c /etc/supervisor/conf.d/supervisord.conf"]