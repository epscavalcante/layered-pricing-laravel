# -------------------------------------------------------
# Base oficial do FrankenPHP (PHP 8.4)
# -------------------------------------------------------
FROM dunglas/frankenphp:1-php8.4

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

# -------------------------------------------------------
# Configurar diretório da aplicação
# -------------------------------------------------------
WORKDIR /var/www
#COPY . .

# Permissões (ambiente de desenvolvimento)
#RUN chmod -R 777 storage bootstrap/cache

# -------------------------------------------------------
# Configurar Caddy/FrankenPHP
# -------------------------------------------------------
# COPY ./docker/Caddyfile /etc/caddy/Caddyfile

# -------------------------------------------------------
# Expor porta padrão
# -------------------------------------------------------
EXPOSE 8000

# -------------------------------------------------------
# Rodar Octane com FrankenPHP em modo dev (watch)
# -------------------------------------------------------
#CMD ["frankenphp", "run", "--workers=4", "--watch"]
#ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0"]
CMD [ "tail", "-f", "/dev/null" ]
