# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copia tu proyecto al directorio del servidor
COPY . /var/www/html/

# Exponer el puerto 80 (Render lo redirige internamente)
EXPOSE 80
