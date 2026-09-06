# =========================
# 1. PHP DEPENDENCIES (Composer)
# =========================
FROM composer:lts AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

# =========================
# 2. FRONTEND ASSETS (React / Vite)
# =========================
FROM node:22 AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# =========================
# 3. RUNTIME (Final Image)
# =========================
FROM serversideup/php:8.4-fpm-nginx

# Enable and tune OPcache for production
ENV PHP_OPCACHE_ENABLE="1" \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="128" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

WORKDIR /var/www/html

USER root

# Clean native ServerSideUp extension installation (single-threaded make)
RUN MAKEFLAGS="-j1" install-php-extensions intl

COPY . .
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set correct storage structure, permissions, and storage symlink
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/bootstrap/cache \
    && ln -sf /var/www/html/storage/app/public /var/www/html/public/storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080