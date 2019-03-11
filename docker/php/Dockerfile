FROM php:7.2-alpine3.8

RUN apk add --update \
    && docker-php-ext-install pdo_mysql opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/*