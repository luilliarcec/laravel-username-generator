# Base image
FROM php:8.2-alpine

# Create app folder
RUN mkdir -p /app

# Set working directory
WORKDIR /app

## Copy Laravel application files
COPY . ./

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

## Install application dependencies
RUN composer install --optimize-autoloader --no-dev

# Run tests
CMD ./vendor/bin/phpunit
