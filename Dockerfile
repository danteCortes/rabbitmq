FROM php:8.3-apache

COPY . /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update && apt install zip unzip

RUN docker-php-ext-install sockets

EXPOSE 80
