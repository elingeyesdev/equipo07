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

echo "Instalando dependencias Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Instalando dependencias npm..."
npm install

echo "Compilando assets con Vite..."
npm run build

echo "Generando APP_KEY..."
php artisan key:generate --force || true

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

echo "Iniciando npm dev en background..."
nohup npm run dev > /tmp/vite.log 2>&1 &

echo "Iniciando PHP-FPM..."
exec php-fpm
