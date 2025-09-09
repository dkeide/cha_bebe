# Usar imagem oficial PHP com Apache
FROM php:8.2-apache

# Atualiza pacotes e instala dependências para PostgreSQL e utilitários
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiar os arquivos do projeto para o container
COPY . /var/www/html/

# Permitir mod_rewrite (opcional, útil para URLs amigáveis)
RUN a2enmod rewrite

# Expor porta 80
EXPOSE 80
