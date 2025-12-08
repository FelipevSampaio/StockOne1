# Dockerfile para Laravel + Node.js
FROM php:8.2-fpm

# Instala extensões do PHP necessárias
RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl libonig-dev netcat-openbsd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Instala Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Define diretório de trabalho
WORKDIR /var/www

# Copia arquivos do projeto
COPY . /var/www

# Permissões
RUN chown -R www-data:www-data /var/www

# Expondo porta padrão do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
