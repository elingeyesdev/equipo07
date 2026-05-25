#!/bin/bash
set -e

cd /var/www

# Crear .env dentro del contenedor si no existe
if [ ! -f .env ]; then
  echo "No existe .env — creando desde .env.example"
  cp .env.example .env
else
  echo ".env ya existe"
fi

if [ -f vendor/autoload.php ]; then
  echo "Dependencias Composer ya instaladas; saltando composer install."
else
  echo "Instalando dependencias Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if grep -q '^APP_KEY=base64:' .env; then
  echo "APP_KEY ya existe."
else
  echo "Generando APP_KEY..."
  php artisan key:generate --force || true
fi

if [ "${RUN_STARTUP_TASKS:-false}" = "true" ]; then
  echo "Cache config..."
  php artisan config:clear || true
  php artisan cache:clear || true

  echo "Permisos storage..."
  chmod -R 777 storage bootstrap/cache || true

  echo "Migraciones + seed..."
  php artisan migrate --force || true
  php artisan db:seed --force || true

  echo "storage:link..."
  php artisan storage:link || true
else
  echo "Saltando tareas automaticas de Artisan. Usa RUN_STARTUP_TASKS=true para activarlas."
fi

echo "Iniciando PHP-FPM..."
exec php-fpm
