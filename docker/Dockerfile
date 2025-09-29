# Usa la imagen base de PHP
FROM php:8.3-fpm

# --- INSTALACIÓN DE DEPENDENCIAS Y EXTENSIONES ---

# 1. Se instala el cliente MariaDB (para el comando 'mysql')
# 2. Se instalan extensiones de PHP (incluyendo Redis)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        nano \
        cron \
        libbz2-dev \
        libicu-dev \
        libonig-dev \
        libzip-dev \
        autoconf \
        pkg-config \
        mariadb-client \
    \
    && docker-php-ext-install -j$(nproc) bcmath bz2 intl mbstring opcache pdo_mysql sockets zip \
    \
    && pecl install redis \
    && docker-php-ext-enable redis \
    \
    # Limpieza
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código de la aplicación
COPY . /var/www/html

# --- CONFIGURACIÓN DE USUARIO, CRON Y PERMISOS ---

# Ajustar el UID del usuario www-data a 1000
RUN usermod -u 1000 www-data

# CORRECCIÓN DEFINITIVA DE SSL/TLS (Error 2026 y 'unknown variable')
# Creamos el archivo .my.cnf para el usuario www-data con 'skip-ssl'
RUN echo "[mysql]\nskip-ssl\n[mysqldump]\nskip-ssl" > /var/www/.my.cnf 

# Copiar configuraciones
COPY ./docker/php.ini /usr/local/etc/php/
COPY ./docker/crontab /etc/cron.d/crontab

# CORRECCIÓN DE CRONTAB y PERMISOS
RUN chmod 0644 /etc/cron.d/crontab \
    && echo "" >> /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab \
    && chown -R www-data:www-data /var/www/html \
    && chown www-data:www-data /var/www/.my.cnf

# Permisos de escritura para directorios de Laravel y normalización
RUN chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && find /var/www/html -type f -exec chmod 664 {} \; \
    && find /var/www/html -type d -exec chmod 775 {} \;

# Copiar y hacer ejecutable el script de entrada (docker-entrypoint.sh)
COPY ./docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- SEGURIDAD Y EJECUCIÓN ---
USER www-data
EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]