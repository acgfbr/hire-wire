# Build frontend assets
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json ./
COPY pnpm-lock.yaml ./
COPY tsconfig.json ./
COPY tsconfig.node.json ./
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./

RUN npm install -g pnpm && \
    pnpm install

COPY resources/ resources/
COPY public/ public/

RUN pnpm run build

# PHP application
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    mysql-client \
    supervisor \
    npm \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    netcat-openbsd

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install pnpm globally
RUN npm install -g pnpm

# Copy project files
COPY . .
COPY --from=frontend /app/public/build public/build

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and make entrypoint script executable
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
