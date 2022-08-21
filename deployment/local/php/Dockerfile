FROM php:8.1-fpm-buster

RUN apt-get update && apt-get install -y \
    git \
    zip \
    icu-devtools libicu-dev \
    libzip-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure \
    intl \
    && docker-php-ext-install \
        pdo_pgsql \
        opcache \
        intl \
        zip \
        sockets

COPY ./deployment/local/php/php-overrides.ini /usr/local/etc/php/conf.d/
COPY ./deployment/local/php/docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony


WORKDIR /var/www
ENV PATH="${PATH}:/var/www:/var/www/vendor/bin"
