# Usa la imagen base de PHP
FROM php:8.3-fpm

# --- INSTALACIÓN DE DEPENDENCIAS Y EXTENSIONES (OPTIMIZADO EN UNA SOLA CAPA) ---

# 1. Instalamos paquetes del sistema y dependencias de compilación
# 2. Instalamos extensiones nativas y Redis (SOLUCIÓN AL ERROR)
# 3. Limpiamos el cache de APT
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
        # Dependencias para PECL
        autoconf \
        pkg-config \
    \
    # Instalamos extensiones nativas (-j$(nproc) para compilación paralela)
    && docker-php-ext-install -j$(nproc) bcmath bz2 intl mbstring opcache pdo_mysql sockets zip \
    \
    # Instalamos y habilitamos la extensión Redis (CORRECCIÓN)
    && pecl install redis \
    && docker-php-ext-enable redis \
    \
    # Limpiamos el cache de APT para reducir el tamaño de la imagen final
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Define el directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código de la aplicación (asumiendo que los archivos están en la carpeta donde se ejecuta `docker compose`)
COPY . /var/www/html

# --- CONFIGURACIÓN DE USUARIO, CRON Y PERMISOS ---

# Ajustar el UID del usuario www-data a 1000 (si es necesario para compatibilidad con el host)
RUN usermod -u 1000 www-data

# ... (Pasos anteriores)

# Copiar configuraciones personalizadas y crontab
COPY ./docker/php.ini /usr/local/etc/php/
COPY ./docker/crontab /etc/cron.d/crontab

# CORRECCIÓN: Agregar una nueva línea al final del archivo crontab
# 1. Aplica permisos
# 2. Usa 'echo "" >>' para añadir un salto de línea al final del archivo
# 3. Instala el crontab
RUN chmod 0644 /etc/cron.d/crontab \
    && echo "" >> /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab \
    && chown -R www-data:www-data /var/www/html

# Permisos de escritura para directorios de Laravel (storage y cache) y normalización
RUN chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && find /var/www/html -type f -exec chmod 664 {} \; \
    && find /var/www/html -type d -exec chmod 775 {} \;

# Copiar y hacer ejecutable el script de entrada (para iniciar cron y php-fpm)
COPY ./docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- SEGURIDAD Y EJECUCIÓN ---

# Ejecutar como usuario no-root por defecto (mejora de seguridad)
USER www-data

# Exponer puerto
EXPOSE 9000

# Usar el script de entrada como ENTRYPOINT principal
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm", "-F"]