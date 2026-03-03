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

# ========================================
# CRITICAL: Set required env vars for Wayfinder
# ========================================
# Generate APP_KEY if not set (needed for artisan commands during build)
RUN if [ -z "$APP_KEY" ]; then \
    echo "APP_KEY not set, generating..."; \
    php artisan key:generate --force; \
    fi

# Set APP_URL if not set (defaults for build)
ENV APP_URL="${APP_URL:-http://localhost}"
ENV ASSET_URL="${ASSET_URL:-}"
ENV APP_ENV=production

# Install PHP deps (needed for artisan during build)
RUN composer install --no-dev --optimize-autoloader

# Install JS deps + build Vite (Wayfinder runs here, PHP exists ✅)
# Use --ignore-scripts to skip any post-install scripts that might fail
RUN npm ci --ignore-scripts && npm run build


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

# Set PORT from Railway environment variable (default to 8080)
ENV PORT="${PORT:-8080}"

EXPOSE 8080

CMD ["sh", "-lc", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force || true && supervisord -c /etc/supervisor/conf.d/supervisord.conf"]
