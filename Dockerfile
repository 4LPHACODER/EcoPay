# ==========================
# EcoPay - Laravel on Render
# ==========================
FROM php:8.2-apache

# ---- System dependencies + PHP extensions ----
RUN apt-get update && apt-get install -y \
    git unzip curl ca-certificates \
    libzip-dev libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql zip \
  && a2enmod rewrite headers \
  && rm -rf /var/lib/apt/lists/*

# ---- Set Apache document root to /public ----
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ---- Composer ----
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---- App files ----
WORKDIR /var/www/html
COPY . .

# ---- Install PHP dependencies ----
# (If your repo includes vendor already, this will still be fine)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ---- Laravel writable folders ----
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# ---- Ensure Apache listens on Render's $PORT ----
# Render provides PORT dynamically. We must bind Apache to it.
RUN printf 'Listen ${PORT}\n' > /etc/apache2/ports.conf

# Also update default vhost to use the same port placeholder
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# ---- Optional: health endpoint without Laravel (helps Render health checks) ----
# Not required, but useful.
RUN echo "OK" > /var/www/html/public/health.txt

# ---- Expose a default, Render will override with $PORT ----
EXPOSE 10000

# ---- Start Apache ----
# IMPORTANT: Do NOT run migrations here unless you really want it at every boot.
CMD ["apache2-foreground"]