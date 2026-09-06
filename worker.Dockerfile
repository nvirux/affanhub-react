# =========================
# 1. VENDOR STAGE (Composer)
# =========================
FROM composer:lts AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Bypass extension checks during build
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

# =========================
# 2. RUNTIME (Final Worker Image)
# =========================
FROM serversideup/php:8.4-cli

WORKDIR /var/www/html

USER root

# Single-threaded build prevents RAM/CPU spikes on VPS
RUN MAKEFLAGS="-j1" install-php-extensions intl zip pdo_mysql gd

COPY . .
COPY --from=vendor /app/vendor /var/www/html/vendor

# Set storage structure and permissions
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Switch to www-data for non-root execution
USER www-data

# Run standard Laravel queue worker
CMD ["php", "artisan", "queue:work", "--tries=3", "--timeout=90"]
