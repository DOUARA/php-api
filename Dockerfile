FROM php:7.3-apache

# Change rootDocument
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable htaccess rewrite rules
RUN a2enmod rewrite  

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

