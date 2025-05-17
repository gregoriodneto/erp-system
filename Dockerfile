FROM php:8.2-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html
COPY .env /var/www/html/.env
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html
RUN composer install
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 80