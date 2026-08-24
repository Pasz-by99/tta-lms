FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

# Ensure sqlite database exists
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache

# Generate app key if missing and run migrations
RUN php artisan key:generate --force || true

EXPOSE 10000

CMD php artisan migrate --force --seed && php artisan serve --host 0.0.0.0 --port 10000
