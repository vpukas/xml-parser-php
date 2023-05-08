FROM php:7.4.3-fpm-alpine3.11

ENV RUN_DEPS \
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \
    postgresql-libs

ENV BUILD_DEPS \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    postgresql-dev

ENV PHP_EXTENSIONS \
    opcache \
    zip \
    gd \
    bcmath \
    pgsql \
    pdo_pgsql

# Copy custom php.ini file to container
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom-php.ini
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Set PHP_INI_SCAN_DIR environment variable
ENV PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d

RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && apk del .build-deps

RUN apk add --no-cache --virtual .run-deps $RUN_DEPS

COPY . /app
VOLUME ["/app"]
