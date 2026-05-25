#!/bin/bash
set -e

cd /var/www

# Crear .env dentro del contenedor si no existe
if [ ! -f .env ]; then
  echo "No existe .env - creando desde .env.example"
  cp .env.example .env
else
  echo ".env ya existe"
fi

if [ ! -f vendor/autoload.php ]; then
  echo "No existe vendor/autoload.php - instalando dependencias Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "Dependencias Composer ya instaladas - omitiendo composer install"
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
  echo "Generando APP_KEY..."
  php artisan key:generate --force || true
else
  echo "APP_KEY ya existe - omitiendo key:generate"
fi

if [ "${RUN_STARTUP_TASKS:-false}" = "true" ]; then
  echo "Cache config..."
  php artisan config:clear || true
  php artisan cache:clear || true

  echo "Permisos storage..."
  chmod -R 777 storage bootstrap/cache || true

  echo "Migraciones..."
  php artisan migrate --force || true

  if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Seeders habilitados..."
    php artisan db:seed --force || true
  else
    echo "Seeders omitidos. Para ejecutarlos usa RUN_SEEDERS=true."
  fi

  echo "storage:link..."
  php artisan storage:link || true
else
  echo "Saltando tareas automaticas de Artisan. Usa RUN_STARTUP_TASKS=true para activarlas."
fi

echo "Iniciando PHP-FPM..."
exec php-fpm
