FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

RUN mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache

EXPOSE 10000

CMD sh -c "php artisan key:generate --force || true; php artisan migrate --force; php artisan db:seed --class=AdminUserSeeder --force || true; php artisan db:seed --class=CategorySeeder --force || true; php artisan db:seed --class=CourseSeeder --force || true; php artisan serve --host 0.0.0.0 --port 10000"