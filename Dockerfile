FROM php:8.1-fpm

RUN apt-get update && apt-get upgrade -y

RUN apt-get install -y git unzip zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www