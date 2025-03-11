FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    zip \
    unzip \
    git \
    curl \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd pdo pdo_pgsql intl mbstring exif bcmath && \
    docker-php-ext-enable gd intl mbstring exif bcmath

RUN pecl install xdebug

COPY application.ini /usr/local/etc/php/conf.d/application.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apk del $PHPIZE_DEPS && \
        rm -rf /var/cache/apk/* /tmp/pear

WORKDIR /var/www/app

CMD ["php-fpm"]
