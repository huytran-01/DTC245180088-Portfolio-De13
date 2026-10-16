# Dockerfile cho ung dung Web Portfolio - DTC245180088 (De 13)
FROM php:8.2-apache

# Cai dat extension mysqli va pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Doi cong lang nghe cua Apache sang 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/' /etc/apache2/sites-available/000-default.conf

# Thiet lap thu muc lam viec
WORKDIR /var/www/html

# Sao chep toan bo ma nguon ung dung vao container
COPY . /var/www/html/

# Tao va phan quyen cac thu muc runtime can thiet de Apache co the chay duoi quyen non-root www-data
RUN mkdir -p /var/run/apache2 /var/lock/apache2 /var/log/apache2 \
    && chown -R www-data:www-data /var/www/html /var/run/apache2 /var/lock/apache2 /var/log/apache2 \
    && chmod -R 755 /var/www/html

# Chay container duoi quyen non-root
USER www-data

# Mo cong 8080
EXPOSE 8080

# Lenh khoi chay Apache o foreground
CMD ["apache2-foreground"]
