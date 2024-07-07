# Base image
FROM php:8.3-alpine

# Create app folder
RUN mkdir -p /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Install dependencies
RUN apk --update add \
    mysql-client \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-configure pdo_pgsql
RUN docker-php-ext-install pdo_mysql pdo_pgsql

## Copy Laravel application files
COPY . .

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

## Install application dependencies
RUN composer update --optimize-autoloader

# Keeping the container active
CMD [ "tail", "-f", "/dev/null" ]
