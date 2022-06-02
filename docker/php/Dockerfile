FROM php:8.1-fpm

RUN apt-get update \
    && apt-get -y install git \
    && apt-get -y install libcurl4-openssl-dev zlib1g-dev libonig-dev libzip-dev

RUN docker-php-ext-install bcmath curl exif iconv mbstring pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer