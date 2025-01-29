FROM php:8.3-cli AS builder

# Instalar as dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    build-essential \
    autoconf \
    zlib1g-dev \
    && docker-php-ext-install zip mysqli

# Instalar a extensão gRPC
RUN pecl install grpc && docker-php-ext-enable grpc

# Instalar e atualizar o Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

# Definir o diretório de trabalho
WORKDIR /app
COPY . .

# Instalar as dependências
RUN composer install

