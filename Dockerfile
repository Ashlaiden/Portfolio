FROM php:8.2-apache

# فعال کردن اکستنشن‌های لازم
RUN docker-php-ext-install pdo pdo_mysql mysqli

# کپی کل پروژه
WORKDIR /var/www/html
COPY . .

# مطمئن شدن از وجود writable و logs و تنظیم مالکیت
RUN mkdir -p public_html/writable logs \
    && chown -R www-data:www-data public_html/writable logs

# اگر لازم داری، document root رو به public_html تنظیم کن
ENV APACHE_DOCUMENT_ROOT /var/www/html/public_html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
