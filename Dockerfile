FROM php:8.2-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p database \
    && chmod 777 database \
    && touch database/restify.db \
    && chmod 666 database/restify.db

# Configurar Apache
RUN a2enmod rewrite
RUN echo '<VirtualHost *:10000>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf

# Expor porta
EXPOSE 10000

# Iniciar Apache
CMD ["apache2ctl", "-D", "FOREGROUND"]
