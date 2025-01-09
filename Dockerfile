# Menggunakan image PHP resmi sebagai base image
FROM php:8.2.20-fpm

# Install beberapa dependensi penting
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set direktori kerja di dalam container
WORKDIR /var/www/html

# Salin semua file dari proyek ke container
COPY . /var/www/html

# Install dependensi Composer
RUN composer install --no-dev --optimize-autoloader

# Expose port 80 untuk aplikasi
EXPOSE 80

# Perintah untuk menjalankan aplikasi
CMD ["php-fpm"]
