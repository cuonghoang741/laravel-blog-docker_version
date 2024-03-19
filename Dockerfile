# Sử dụng bản PHP với Apache
FROM php:8.0-fpm

# Cài đặt các phụ thuộc
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \z
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip exif pcntl

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Cấu hình Apache & PHP
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Cài đặt các phụ thuộc của Laravel thông qua Composer
RUN composer install

# Expose port 80
EXPOSE 80
