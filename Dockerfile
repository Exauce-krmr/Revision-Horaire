FROM php:8.5-apache

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite && \
    printf '<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/allow-override.conf && \
    a2enconf allow-override