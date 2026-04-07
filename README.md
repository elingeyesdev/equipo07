<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<h1 align="center">Mercado Agrícola / AgroVida</h1>

<p align="center">
Sistema web desarrollado en Laravel para la gestión de un mercado agrícola.
</p>

---

## Descripción general

Mercado Agrícola / AgroVida es una plataforma web que permite la publicación, visualización y compra de productos agrícolas, incluyendo **animales**, **maquinaria** y **productos orgánicos**.  
El sistema gestiona usuarios con distintos roles (cliente, vendedor y administrador), incorpora reportes administrativos y expone una API REST preparada para futuras integraciones móviles.

---

## Instalación nativa (sin contenedores)

### Requisitos
- PHP 8.2 o superior
- Composer
- Node.js y npm
- PostgreSQL
- Git

### Pasos

1. Clonar el repositorio:
```bash
git clone https://github.com/nicolealiendreah/Mercado-Agricola-Proyecto
cd Proyecto-Agricola
````

2. Crear el archivo de entorno:

```bash
cp .env.example .env
```

Configurar las variables de base de datos en el archivo `.env`.

3. Instalar dependencias:

```bash
composer install
npm install
npm run build
```

4. Generar la clave de la aplicación:

```bash
php artisan key:generate
```

5. Ejecutar migraciones y seeders:

```bash
php artisan migrate:fresh --seed
```

6. Crear el enlace de almacenamiento:

```bash
php artisan storage:link
```

7. Levantar el servidor:

```bash
php artisan serve
```

Acceso desde el navegador:

```
http://localhost:8000
```

---

## Instalación con contenedores (Docker)

### Requisitos

* Docker
* Docker Compose

---

### Archivos necesarios en la raíz del proyecto

* `docker-compose.yml`
* `Dockerfile`
* `entrypoint.sh`
* `nginx.conf`
* `.env.docker`
* `.dockerignore` (recomendado)

---

### Pasos

#### 1. Clonar el repositorio

```bash
git clone https://github.com/nicolealiendreah/Mercado-Agricola-Proyecto
cd Proyecto-Agricola
```

---

#### 2. Crear el archivo de entorno para Docker

```bash
cp .env.example .env.docker
```

> ⚠️ Docker carga las variables de entorno desde el archivo `.env.docker`, configurado en `docker-compose.yml` mediante `env_file`.

---

#### 3. Construir y levantar los contenedores

```bash
docker compose up -d --build
```

---

#### 4. Configurar la aplicación dentro del contenedor Laravel

```bash
docker compose exec laravel php artisan key:generate
docker compose exec laravel php artisan migrate --seed
docker compose exec laravel php artisan storage:link
```

> Si necesitas reiniciar completamente la base de datos:
>
> ```bash
> docker compose down -v
> docker compose up -d --build
> docker compose exec laravel php artisan migrate --seed
> ```

---

### Acceso al sistema

####  Modo local (pruebas rápidas)

Si en `docker-compose.yml` está habilitado:

```yaml
ports:
  - "8080:80"
```

Acceso desde el navegador:

```
http://localhost:8080
```

---

####  Modo integración (proxy / otros proyectos)

En modo integración, **Nginx no expone puertos al host** (`expose: 80`).
El acceso se realiza mediante un **proxy externo** conectado a la red Docker:

* `proxy-network`

Este modo permite integrar AgroVida con otros proyectos o servicios.

---

## Verificación

* La aplicación web debe cargar correctamente.
* Verificar conexión a base de datos y estado general:

```bash
docker compose exec laravel php artisan about
```

* Verificar migraciones:

```bash
docker compose exec laravel php artisan migrate:status
```

* Verificar enlace de almacenamiento:

```bash
docker compose exec laravel php artisan storage:link
```

---

## Usuario administrador de prueba

Creado automáticamente por los seeders:

* **Correo:** [admin@agrovida.com](mailto:admin@agrovida.com)
* **Contraseña:** admin123

Se recomienda cambiar estas credenciales en entornos de producción.

---

## Notas

* El archivo `.env.example` no contiene credenciales reales.
* Para producción se recomienda configurar:

```env
APP_ENV=production
APP_DEBUG=false
