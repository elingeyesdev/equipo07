# AgroVida / Mercado Agricola

Sistema web en Laravel para publicar, visualizar, vender y alquilar productos
agricolas: ganado, maquinaria y productos organicos. Incluye roles de cliente,
vendedor y administrador, carrito, pedidos, solicitudes al vendedor, reportes,
mapas y flujo de alquiler de maquinaria.

## Tecnologias principales

- Laravel 12
- PHP 8.2+
- PostgreSQL
- Blade para las vistas
- Vite, Tailwind/PostCSS y CSS propio
- Docker Compose con servicios de Laravel, Nginx, PostgreSQL y assets

## Levantar el proyecto local con Docker

Esta es la forma recomendada para trabajar el proyecto en local.

### Requisitos

- Git
- Docker
- Docker Compose

### Primer arranque

Desde la raiz del proyecto:

```bash
docker compose up -d --build
docker compose run --rm assets
docker compose exec laravel php artisan key:generate --force
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

Abrir en el navegador:

```text
http://localhost:8081
```

### Si ya habias levantado el proyecto antes

Normalmente basta con:

```bash
docker compose up -d
```

Si hay cambios nuevos en CSS/JS:

```bash
docker compose run --rm assets
```

Si hay migraciones nuevas:

```bash
docker compose exec laravel php artisan migrate
```

### Ver estado de contenedores

```bash
docker compose ps
```

### Apagar el proyecto

```bash
docker compose down
```

### Reiniciar todo desde cero

Esto borra la base de datos local y vuelve a cargar datos de prueba.

```bash
docker compose down -v
docker compose up -d --build
docker compose run --rm assets
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

## Comandos utiles durante desarrollo

Entrar al contenedor de Laravel:

```bash
docker compose exec laravel bash
```

Ejecutar migraciones:

```bash
docker compose exec laravel php artisan migrate
```

Recrear base de datos con seeders:

```bash
docker compose exec laravel php artisan migrate:fresh --seed
```

Limpiar cache de Laravel:

```bash
docker compose exec laravel php artisan optimize:clear
```

Compilar vistas Blade para revisar errores:

```bash
docker compose exec laravel php artisan view:cache
docker compose exec laravel php artisan view:clear
```

Ver informacion de la app:

```bash
docker compose exec laravel php artisan about
```

Compilar assets:

```bash
docker compose run --rm assets
```

## Usuarios de prueba

Administrador:

```text
Correo: admin@agrovida.com
Clave: admin123
```

Vendedores demo creados por seeders:

```text
Correo: vendedor1@agrovida.test
Clave: password
```

Tambien existen `vendedor2@agrovida.test`, `vendedor3@agrovida.test`, etc.,
dependiendo de los datos generados por `DemoDataSeeder`.

Para probar como comprador, puedes registrar un usuario nuevo desde la pantalla
de registro o usar el flujo que el proyecto tenga cargado en tu base local.

## Instalacion nativa sin Docker

Usa esta opcion solo si ya tienes PHP, Composer, Node.js y PostgreSQL instalados.

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

En `.env`, si usas PostgreSQL local, revisa:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mercado_agricola
DB_USERNAME=postgres
DB_PASSWORD=root
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

La app quedara disponible en:

```text
http://localhost:8000
```

## Estructura del codigo

### Rutas

Las rutas principales estan en:

```text
routes/web.php
```

Ahi se conectan las URL con los controladores. Por ejemplo:

- Carrito: rutas `cart.*`
- Pedidos del comprador: rutas `pedidos.*`
- Solicitudes del vendedor: rutas `vendedor.solicitudes.*`
- CRUD de ganado, maquinaria y organicos
- Rutas de administrador y reportes

Las rutas de API estan en:

```text
routes/api.php
```

### Controladores: logica de cada flujo

Estan en:

```text
app/Http/Controllers
```

Archivos importantes:

- `CartController.php`: agregar productos al carrito, actualizar cantidades,
  manejar horas/dias de maquinaria y eliminar items.
- `PedidoController.php`: crear pedidos desde el carrito y mostrar pedidos del
  comprador.
- `VendedorSolicitudController.php`: pantalla del vendedor para aceptar,
  rechazar, avanzar estados de alquiler y finalizar solicitudes.
- `AdminPedidoController.php`: gestion de pedidos desde administrador.
- `GanadoController.php`: CRUD y publicacion de ganado.
- `MaquinariaController.php`: CRUD y publicacion de maquinaria.
- `OrganicoController.php`: CRUD y publicacion de productos organicos.
- `ReporteController.php`: reportes administrativos.
- `SolicitudVendedorController.php`: solicitud para convertirse en vendedor.

### Modelos: representacion de tablas y relaciones

Estan en:

```text
app/Models
```

Modelos centrales:

- `User.php`: usuarios y roles.
- `Role.php`: roles del sistema.
- `Ganado.php`: producto tipo animal/ganado.
- `Maquinaria.php`: producto tipo maquinaria.
- `Organico.php`: producto organico.
- `CartItem.php`: item guardado en carrito.
- `Pedido.php`: pedido principal del comprador.
- `PedidoDetalle.php`: producto dentro de un pedido; tambien maneja la solicitud
  al vendedor y el seguimiento del alquiler de maquinaria.
- `SolicitudVendedor.php`: solicitud de usuario para ser vendedor.

En Laravel, normalmente el controlador recibe la peticion, usa modelos para leer
o guardar datos, y finalmente devuelve una vista Blade.

### Validaciones de formularios

Estan en:

```text
app/Http/Requests
```

Ejemplos:

- `StoreMaquinariaRequest.php`
- `UpdateMaquinariaRequest.php`
- `StoreOrganicoRequest.php`
- `UpdateOrganicoRequest.php`
- `LoginRequest.php`
- `RegisterRequest.php`

Estos archivos separan las reglas de validacion del controlador.

### Vistas y diseno

Las vistas Blade estan en:

```text
resources/views
```

Carpetas importantes:

- `resources/views/layouts`: plantillas base del sistema.
- `resources/views/public`: pantallas publicas, landing, home y anuncios.
- `resources/views/cart`: carrito de compras.
- `resources/views/pedidos`: pedidos vistos por el comprador.
- `resources/views/vendedor/solicitudes`: solicitudes vistas por el vendedor.
- `resources/views/admin`: panel administrativo, pedidos y reportes.
- `resources/views/ganados`: formularios y detalle de ganado.
- `resources/views/maquinarias`: formularios, wizard y detalle de maquinaria.
- `resources/views/organicos`: formularios y detalle de organicos.

CSS propio:

```text
public/css/custom.css
```

Assets de frontend:

```text
resources/css
resources/js
vite.config.js
package.json
```

### Base de datos

Migraciones:

```text
database/migrations
```

Las migraciones definen y modifican tablas. Ejemplos importantes:

- tablas de usuarios y roles
- tablas de ganado, maquinaria y organicos
- tablas de carrito y pedidos
- columnas de destino del pedido
- columnas de alquiler: `alquiler_unidad` y `estado_alquiler`

Seeders:

```text
database/seeders
```

Archivos importantes:

- `RoleSeeder.php`: crea roles.
- `AdminUserSeeder.php`: crea el usuario administrador.
- `DemoDataSeeder.php`: crea datos demo de vendedores, ganado, maquinaria y
  organicos.
- `DatabaseSeeder.php`: ejecuta los seeders principales.

### Archivos de configuracion

- `docker-compose.yml`: define servicios `laravel`, `nginx`, `db` y `assets`.
- `Dockerfile`: imagen usada para el contenedor Laravel.
- `nginx.conf`: configuracion del servidor web Nginx.
- `.env.example`: ejemplo para variables de entorno.
- `.env.docker`: variables usadas por Docker Compose.
- `entrypoint.sh`: prepara dependencias dentro del contenedor Laravel.

## Flujo general del sistema

1. El usuario se registra o inicia sesion.
2. Si es vendedor, puede publicar ganado, maquinaria u organicos.
3. El comprador ve anuncios y agrega productos al carrito.
4. Al procesar el carrito se crea un `Pedido`.
5. Cada producto del pedido se guarda como `PedidoDetalle`.
6. El vendedor ve esas solicitudes en `Solicitudes de productos`.
7. El vendedor acepta o rechaza.
8. Si es maquinaria, el vendedor puede avanzar el estado del alquiler:

```text
Aceptado -> En camino al comprador -> Entregado -> En uso -> En retorno -> Devuelto -> Finalizado
```

9. El comprador puede ver el seguimiento desde el detalle de su pedido.

## Notas importantes

- `.env` no se versiona. Cada maquina debe crear el suyo.
- `vendor`, `node_modules` y `public/build` se regeneran con Composer/npm o con
  Docker.
- Si las imagenes no se ven, ejecutar:

```bash
docker compose exec laravel php artisan storage:link
```

- Si una vista no muestra cambios recientes, ejecutar:

```bash
docker compose exec laravel php artisan view:clear
```

- Para probar comprador y vendedor al mismo tiempo, usa dos navegadores distintos
  o una ventana normal y una ventana privada.
