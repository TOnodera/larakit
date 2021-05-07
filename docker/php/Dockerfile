FROM php:8.0-fpm
COPY php.ini /usr/local/etc/php/

RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

RUN apt-get update \
    && apt-get install -y zlib1g-dev libzip-dev mariadb-client git unzip \
    && docker-php-ext-install zip pdo_mysql

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \ 
    && apt-get update \
    && apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN adduser --disabled-password --gecos '' php

WORKDIR /home/php/workspace
RUN chown -R php:php /home/php/workspace