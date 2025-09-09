# Usar imagem oficial PHP com Apache
FROM php:8.2-apache

# Copiar os arquivos do projeto para o container
COPY . /var/www/html/

# Habilitar extensão do PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# Expor a porta 80
EXPOSE 80