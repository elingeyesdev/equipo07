# AgroVida / Mercado Agricola

Sistema web en Laravel para publicar, visualizar y comprar productos agricolas:
ganado, maquinaria y productos organicos. Incluye roles de cliente, vendedor y
administrador, reportes y una API preparada para integraciones.

## Opcion recomendada: Docker

Esta es la forma mas simple para levantar el proyecto despues de clonarlo en otra
maquina.

### Requisitos

- Git
- Docker Desktop con Docker Compose

### Arranque rapido en Windows

Desde PowerShell, en la raiz del proyecto:

```powershell
.\scripts\setup-docker.ps1
```

El script construye los contenedores, instala dependencias PHP si faltan, compila
los assets, genera la clave de Laravel, ejecuta migraciones con seeders y crea el
enlace de storage.

Cuando termine, abre:

```text
http://localhost:8081
```

Usuario administrador de prueba:

```text
Correo: admin@agrovida.com
Clave: admin123
```

### Arranque manual con Docker

```bash
docker compose up -d --build
docker compose run --rm assets
docker compose exec laravel php artisan key:generate --force
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

Para ver el estado:

```bash
docker compose ps
docker compose exec laravel php artisan about
```

Para reiniciar la base de datos desde cero:

```bash
docker compose down -v
docker compose up -d --build
docker compose run --rm assets
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

## Instalacion nativa

Usa esta opcion solo si ya tienes PHP, Composer, Node.js y PostgreSQL instalados
en tu equipo.

### Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- PostgreSQL

### Pasos

```bash
cp .env.example .env
composer install
npm install
npm run build
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Si usas PostgreSQL local, ajusta en `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mercado_agricola
DB_USERNAME=postgres
DB_PASSWORD=root
```

La app quedara disponible en:

```text
http://localhost:8000
```

## Archivos importantes

- `docker-compose.yml`: define Laravel, Nginx, PostgreSQL y el servicio de assets.
- `.env.docker`: variables usadas por Docker Compose.
- `.env.example`: base para crear `.env` local.
- `entrypoint.sh`: prepara dependencias PHP y clave de Laravel dentro del contenedor.
- `scripts/setup-docker.ps1`: arranque completo para Windows.

## Notas

- `.env` no se versiona. Cada maquina debe generar su propio archivo.
- `vendor`, `node_modules` y `public/build` tampoco se versionan; se regeneran con
  Composer/npm o con el script Docker.
- En produccion cambia `APP_ENV`, `APP_DEBUG`, credenciales y la clave del usuario
  administrador de prueba.
