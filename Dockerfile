FROM php:8.3-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpq-dev \
    && docker-php-ext-install zip pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Run database migrations
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000