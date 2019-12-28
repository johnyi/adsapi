FROM php:7.2-fpm-alpine

COPY php.ini /usr/local/etc/php/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY supervisord.conf /etc/supervisord.conf
COPY src/ /var/www/html/

RUN apk add --update --no-cache autoconf g++ make freetype libjpeg-turbo libpng freetype-dev libpng-dev libjpeg-turbo-dev libmcrypt-dev libxml2-dev libzip-dev postgresql-dev supervisor tzdata \
    && docker-php-ext-install bcmath gd pdo pdo_pgsql soap zip \
    && pecl install grpc \
    && pecl install mcrypt-1.0.1 \
    && pecl install protobuf \
    && pecl install redis \
    && docker-php-ext-enable grpc mcrypt protobuf redis

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && composer install

CMD ["supervisord", "-n", "-c", "/etc/supervisord.conf"]
