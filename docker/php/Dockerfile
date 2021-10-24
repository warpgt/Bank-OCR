FROM php:7.4-fpm-alpine

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN \
    apk add --no-cache tzdata \
    && rm -f /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime