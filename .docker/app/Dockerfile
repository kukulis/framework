FROM php:8.3-fpm-bullseye

RUN apt-get update
RUN apt-get -y install less vim wget git

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions

RUN chmod uga+x /usr/bin/install-php-extensions \
    && sync \
    && install-php-extensions zip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup --gid=1000 app
RUN adduser --disabled-password --quiet --uid=1000 --gid=1000 --no-create-home --gecos GECOS app



