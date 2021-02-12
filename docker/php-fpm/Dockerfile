FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    acl \
    nodejs \
    yarn \
    && apt-get clean

RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

ADD ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

WORKDIR "/app"

COPY --chown=root:www-data ./ ./

RUN composer install -o

RUN setfacl -dR -m u:www-data:rwX -m u:root:rwX var
RUN setfacl -R -m u:www-data:rwX -m u:root:rwX var

CMD ["yarn", "install"]
CMD ["yarn", "run build"]

CMD ["php-fpm", "--nodaemonize"]