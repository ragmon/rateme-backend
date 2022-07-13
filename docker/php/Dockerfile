#FROM composer as composer
#COPY . /app
#RUN composer install --ignore-platform-reqs --no-scripts

FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get -y install git \
    && apt-get -y install build-essential libcurl4-openssl-dev zlib1g-dev libfreetype6-dev libonig-dev libzip-dev libpng-dev libjpeg-dev zip

RUN docker-php-ext-configure gd --enable-gd --with-jpeg

RUN docker-php-ext-install bcmath curl exif iconv mbstring pdo pdo_mysql zip gd pcntl bcmath

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -r -g 1000 admin && useradd -u 1000 -r -g admin admin

#USER special
#
#COPY src/composer.json composer.json
#
#RUN composer install

#WORKDIR /var/www/html

#CMD bash -c "composer install"