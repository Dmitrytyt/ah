FROM php:8.2-fpm-bookworm
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /.composer

RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo -u 1000 ubuntu \
    && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
USER ubuntu

WORKDIR /var/www/html

CMD ["php-fpm"]
