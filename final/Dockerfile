FROM php:7.2-apache


RUN set -ex \
    && apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv zip pdo_mysql mysqli \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd 

RUN set -ex \
    && cd / \
    && mkdir -p /var/lib/php/sessions \
    && chown www-data:www-data -R /var/lib/php/sessions \
    && rm -rf /var/www/* \
    && a2enmod rewrite


COPY www/ /var/www/

RUN set -ex \
    && cd /var/www/ \
    && chown root:root -R . \
    && chmod 0755 -R . \
    && chmod 0777 -R runtime \
    && chown www-data:www-data -R html/uploads/ html/flag/

