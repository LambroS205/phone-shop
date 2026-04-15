FROM php:8.2-fpm

# Cài đặt extension PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Giữ lại user/group mặc định để tránh lỗi phân quyền file
RUN usermod -u 1000 www-data
