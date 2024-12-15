FROM php:7.2-apache

WORKDIR /var/www/html

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y git curl zip libzip-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

COPY . .

USER root

RUN npm install

RUN composer install

RUN mkdir -p /var/www/html/public/build
RUN chown -R www-data:www-data /var/www

RUN npm run build

COPY ./000-default.conf /etc/apache2/sites-available/

RUN a2enmod rewrite

RUN service apache2 restart

USER www-data

EXPOSE 80 443