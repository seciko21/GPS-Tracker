#!/bin/bash
set -e

# Inicia el servicio cron en segundo plano
echo "Starting cron service..."
service cron start

# Ejecuta el comando principal (php-fpm -F) en primer plano.
# El exec se asegura de que la señal de apagado de Docker se envíe correctamente.
exec "$@"