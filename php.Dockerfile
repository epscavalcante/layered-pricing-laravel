# -------------------------------------------------------
# Base oficial do FrankenPHP (PHP 8.4)
# -------------------------------------------------------
FROM dunglas/frankenphp:1-php8.4

# set your user name, ex: user=ourname
ARG user=application
ARG uid=1000

# -------------------------------------------------------
# Instalar dependências do sistema (APT, não APK)
# -------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    bash \
    supervisor \
    libpq-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------------
# Instalar extensões do PHP
# -------------------------------------------------------
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    intl \
    bcmath \
    pcntl \
    gd \
    opcache

# -------------------------------------------------------
# Instalar Composer
# -------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# -------------------------------------------------------
# Configurar diretório da aplicação
# -------------------------------------------------------
#COPY . .

# Install redis
# RUN pecl install -o -f redis \
#     &&  rm -rf /tmp/pear \
#     &&  docker-php-ext-enable redis


# Enable xDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug && \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www

# Permissões (ambiente de desenvolvimento)
#RUN chmod -R 777 storage bootstrap/cache

# -------------------------------------------------------
# Configurar Caddy/FrankenPHP
# -------------------------------------------------------
# COPY ./docker/Caddyfile /etc/caddy/Caddyfile

# -------------------------------------------------------
# Expor porta padrão
# -------------------------------------------------------
# EXPOSE 8000

COPY ./php.ini /usr/local/etc/php/conf.d/custom.ini


USER $user

# -------------------------------------------------------
# Rodar Octane com FrankenPHP em modo dev (watch)
# -------------------------------------------------------
#CMD ["frankenphp", "run", "--workers=4", "--watch"]
#ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0"]
CMD [ "tail", "-f", "/dev/null" ]
