FROM php:8.2-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar projeto
WORKDIR /var/www/html
COPY . .

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p database && chmod 777 database \
    && touch database/restify.db && chmod 666 database/restify.db

# Configurar Apache
RUN a2enmod rewrite && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Criar script de start
RUN echo '#!/bin/bash\n\
PORT=${PORT:-10000}\n\
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf\n\
sed -i "s|/var/www/html|/var/www/html/public|g" /etc/apache2/sites-available/000-default.conf\n\
echo "<Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf\n\
echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /start.sh && chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
