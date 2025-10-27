# Usa una imagen oficial de PHP con Apache (versión 8.2)
FROM php:8.2-apache

# Instala extensiones necesarias (por ejemplo, MySQLi y PDO)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia todo tu proyecto dentro del servidor web
COPY . /var/www/html/

# Expone el puerto 80 (Render lo usará automáticamente)
EXPOSE 80
