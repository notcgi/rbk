# base stage for all over versions
FROM php:8.0-fpm AS base
WORKDIR "/app"

RUN apt update
RUN apt install -y zip unzip libxml2-dev

RUN docker-php-ext-install soap \
	&& docker-php-ext-enable soap

RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug

RUN pecl install redis \
	&& docker-php-ext-enable redis

FROM base AS builder
COPY --from=composer:2.1.3 /usr/bin/composer /usr/bin/composer
VOLUME composer-cache:/tmp/
COPY . ./
RUN composer install  \
    --no-ansi \
    --no-interaction \
    --no-scripts
