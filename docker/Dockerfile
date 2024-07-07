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

# Install SQL Server Drivers
RUN wget https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/msodbcsql18_18.1.2.1-1_amd64.apk && \
  wget https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/mssql-tools18_18.1.1.1-1_amd64.apk && \
  apk add --allow-untrusted msodbcsql18_18.1.2.1-1_amd64.apk && \
  apk add --allow-untrusted mssql-tools18_18.1.1.1-1_amd64.apk && \
  apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS unixodbc-dev && \
  pecl install pdo_sqlsrv && \
  docker-php-ext-enable pdo_sqlsrv && \
  apk del .phpize-deps && \
  rm msodbcsql18_18.1.2.1-1_amd64.apk && \
  rm mssql-tools18_18.1.1.1-1_amd64.apk

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
