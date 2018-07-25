FROM php:7.0-fpm

RUN docker-php-ext-install pdo pdo_mysql

VOLUME /project
WORKDIR /project

RUN mkdir -p --mode=0777 /var/www/templates_cmpl && chmod a+rwx /var/www/templates_cmpl
