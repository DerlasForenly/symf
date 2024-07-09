# Use the official PHP image as the base image
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl mbstring pdo pdo_mysql pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Symfony project files to the container
COPY . .

# Install Symfony dependencies
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest --optimize-autoloader

# Change the owner of the project files to the web server user
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
