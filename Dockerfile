FROM php:8.3-cli

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install zip pdo pdo_pgsql gd

# Configure PHP settings for larger file uploads
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "upload_max_filesize = 120M" > "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "post_max_size = 125M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "memory_limit = 512M" >> "$PHP_INI_DIR/conf.d/uploads.ini"

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create storage link and optimize
RUN php artisan storage:link --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose the port
EXPOSE 10000

# Start the queue worker in the background, then run migrations and start the server
CMD bash -c "php artisan queue:work & php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000"