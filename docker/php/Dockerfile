FROM php:8.3-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS postgresql-dev icu-dev libzip-dev linux-headers git unzip \
    && docker-php-ext-install pdo_pgsql intl zip bcmath pcntl opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
