FROM node:lts as builder
WORKDIR /app
ADD src/package.json /app
RUN npm install
COPY src /app
RUN npm run prod

FROM php:7.4-apache
RUN apt update \
    && apt install -y \
        g++ \
        make \
        zip \
        unzip \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
        libssh2-1-dev \
    && pecl install \
        mongodb \
        ssh2-1.3.1 \
        redis \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
    && docker-php-ext-enable \
        mongodb \
        ssh2 \
        redis
RUN a2enmod rewrite
WORKDIR /var/www/html/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY php/php.ini "$PHP_INI_DIR/conf.d/php.ini"
COPY apache/conf-available/*.conf /etc/apache2/conf-available/
COPY apache/default.conf /etc/apache2/sites-enabled/000-default.conf
COPY apache/error/*.html /usr/share/apache2/error/
COPY --chown=www-data:www-data --from=builder /app /var/www/html/
RUN composer install