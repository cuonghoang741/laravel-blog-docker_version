FROM php:8.2-fpm
ARG user
ARG uid
# Cài đặt các phụ thuộc
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \



# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Cấu hình Apache & PHP
COPY . /var/www/html
#RUN chown -R www-data:www-data /var/www/html \
#    && a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html
# Create system user to run Composer and Artisan Commands


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Expose port 80
#EXPOSE 80
