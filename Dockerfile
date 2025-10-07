FROM php:8.2-apache

WORKDIR /var/www/html

# نصب extensionهای لازم برای CI4
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY . .

# اجازه نوشتن روی writable و cache
RUN chown -R www-data:www-data /var/www/html/writable

EXPOSE 80
