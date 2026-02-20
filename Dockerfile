# ============================================
# Stage 1: Composer Dependencies
# ============================================
FROM composer:2 AS composer

WORKDIR /app

RUN apk add --no-cache icu-dev && docker-php-ext-install intl

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts


# ============================================
# Stage 2: Frontend Assets
# ============================================
FROM node:22-alpine AS node

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ============================================
# Stage 3: Production Image
# ============================================
FROM php:8.4-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    gd \
    intl \
    bcmath \
    zip \
    mbstring \
    pcntl \
    opcache \
    && apk del $PHPIZE_DEPS linux-headers \
    && rm -rf /var/cache/apk/*

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# OPcache config
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.save_comments=1" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# PHP upload/memory config
RUN echo "upload_max_filesize=20M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "post_max_size=20M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "memory_limit=256M" >> "$PHP_INI_DIR/conf.d/uploads.ini"

WORKDIR /var/www/html

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy Supervisor config
RUN mkdir -p /etc/supervisor.d /var/log/supervisor
COPY docker/supervisord.conf /etc/supervisor.d/supervisord.ini

# Copy application
COPY --from=composer /app/vendor ./vendor
COPY . .
COPY --from=node /app/public/build ./public/build

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create storage structure
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
