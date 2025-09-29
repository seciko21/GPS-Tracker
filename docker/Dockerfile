# Usa la imagen base de PHP
FROM php:8.3-fpm

# --- INSTALACIÓN DE DEPENDENCIAS Y EXTENSIONES ---

# Se combina la instalación de paquetes, dependencias de compilación, extensiones PHP y limpieza.
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
    \
    && docker-php-ext-install -j$(nproc) bcmath bz2 intl mbstring opcache pdo_mysql sockets zip \
    \
    # Corrección de Redis
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

# Copiar configuraciones
COPY ./docker/php.ini /usr/local/etc/php/
COPY ./docker/crontab /etc/cron.d/crontab

# CORRECCIÓN DE CRONTAB: Añade salto de línea y carga el crontab
RUN chmod 0644 /etc/cron.d/crontab \
    && echo "" >> /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab \
    && chown -R www-data:www-data /var/www/html

# Permisos de escritura para directorios de Laravel y normalización
RUN chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && find /var/www/html -type f -exec chmod 664 {} \; \
    && find /var/www/html -type d -exec chmod 775 {} \;

# Copiar y hacer ejecutable el script de entrada (docker-entrypoint.sh)
COPY ./docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- SEGURIDAD Y EJECUCIÓN ---

# Ejecutar como usuario no-root por defecto
USER www-data

# Exponer puerto
EXPOSE 9000

# Usar el script de entrada como ENTRYPOINT principal
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]