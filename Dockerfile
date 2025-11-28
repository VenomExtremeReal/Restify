FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p database && chmod 777 database \
    && touch database/restify.db && chmod 666 database/restify.db

# Configurar Apache para usar /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar porta dinâmica
RUN echo '#!/bin/bash\n\
PORT=${PORT:-10000}\n\
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf\n\
sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
