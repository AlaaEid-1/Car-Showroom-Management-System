# Stage 1: Build Node.js assets
FROM node:20-alpine AS node_modules

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# Stage 2: Build PHP application
FROM php:8.4-fpm-alpine


# Install system dependencies
RUN apk add --no-cache \
    mariadb-connector-c-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    bash


# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo \
    pdo_mysql \
    mbstring \
    pcntl \
    bcmath \
    zip


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Set working directory
WORKDIR /var/www/html


# Copy application files
COPY . .


# Copy compiled frontend assets
COPY --from=node_modules /app/public/build ./public/build


# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction


# Copy Docker configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh


RUN chmod +x /usr/local/bin/entrypoint.sh


# Copy TiDB/MySQL SSL certificate
COPY docker/certs/mysql-cert.pem /etc/ssl/certs/mysql-cert.pem


# Permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache


# Render port
EXPOSE 80


# Start application
ENTRYPOINT ["entrypoint.sh"]
