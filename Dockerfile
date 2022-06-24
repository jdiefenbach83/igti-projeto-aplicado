FROM php:8.0-apache

RUN a2enmod rewrite   

RUN apt-get update 

RUN apt-get install -y \
    apt-utils \
    libzip-dev \
    unzip \
    git \
    wget \
    --no-install-recommends

RUN apt-get clean

RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo mysqli pdo_mysql bcmath;

RUN wget https://getcomposer.org/download/2.3.5/composer.phar \ 
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf 

WORKDIR /var/www

CMD ["apache2-foreground"]
