# Usar imagem oficial PHP com Apache
FROM php:8.2-apache

# Atualiza pacotes e instala dependências do PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean

# Copiar os arquivos do projeto para o container
COPY . /var/www/html/

# Permitir mod_rewrite (opcional, útil para URLs amigáveis)
RUN a2enmod rewrite

# Expor a porta 80
EXPOSE 80
