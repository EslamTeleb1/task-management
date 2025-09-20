# Use official PHP 8.2 FPM image
FROM php:8.2-fpm

# Arguments for user
ARG UID=1000
ARG GID=1000

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    npm \
    nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create laravel user
RUN useradd -G www-data,root -u $UID -d /home/laravel laravel
RUN chown -R laravel:laravel /var/www/html
USER laravel

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Run Laravel optimization, migrations and seeders
RUN php artisan optimize:clear
RUN php artisan migrate --force
RUN php artisan db:seed --class="Modules\Auth\Database\Seeders\AuthDatabaseSeeder" --force
RUN php artisan db:seed --class="Modules\User\Database\Seeders\UserDatabaseSeeder" --force
RUN php artisan db:seed --class="Modules\Task\Database\Seeders\TaskDatabaseSeeder" --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
