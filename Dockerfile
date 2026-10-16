# Step 1: Build Vite assets using Node
FROM node:20-alpine AS asset-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Step 2: Build PHP App using richarvey/nginx-php-fpm
FROM richarvey/nginx-php-fpm:3.1.6

# Install PostgreSQL runtime library, dev libraries, and PDO pgsql extension
RUN apk add --no-cache libpq postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apk del postgresql-dev

WORKDIR /var/www/html
COPY . .
RUN chmod +x /var/www/html/scripts/*.sh

# Copy built assets from builder
COPY --from=asset-builder /app/public/build ./public/build

# Install dependencies during Docker build to speed up container boot
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader

# Container configurations
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV PHP_CATCHALL 1

CMD ["/start.sh"]
