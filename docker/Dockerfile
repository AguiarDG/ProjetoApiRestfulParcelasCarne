# Etapa de construção - Composer
FROM composer:latest AS build

WORKDIR /var/www/application

# Copia o arquivo composer.json e composer.lock
COPY application/composer.json application/composer.lock ./

# Instala as dependências do Composer
RUN composer install --no-interaction --prefer-dist --no-scripts

# Copia o restante do código do aplicativo
COPY application ./

# Copia .env.example para .env
RUN cp .env.example .env

# Executa os scripts do Composer
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump \
    && composer run-script post-root-package-install \
    && composer run-script post-create-project-cmd

# Executa as otimizações do Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Etapa de produção - PHP
FROM php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl \
    libonig-dev

# Instala extensões do PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif

# Copie as dependências do Composer e o código do aplicativo
COPY --from=build /var/www/application /var/www/application

# Define o diretório de trabalho
WORKDIR /var/www/application

# Define permissões para o diretório storage e bootstrap/cache
RUN chown -R www-data:www-data /var/www/application/storage /var/www/application/bootstrap/cache \
    && chmod -R 775 /var/www/application/storage /var/www/application/bootstrap/cache

# Expoe a porta que o PHP-FPM vai escutar
EXPOSE 9000

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]
