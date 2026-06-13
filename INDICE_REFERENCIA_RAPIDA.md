# 📑 Índice y Referencia Rápida - AgroVida Docker

## 🎯 Índice de Archivos Generados

```
📚 DOCUMENTACIÓN (Lee en este orden):

1. ⭐ QUICK_START.md (5 min)
   └─ Empezar aquí - 2 opciones de setup + troubleshooting

2. 📗 PLAN_DOCKER_CODESPACES.md (15 min)
   └─ Plan detallado completo - 6 fases, todos los comandos

3. 📕 TECH_DECISIONS.md (10 min)
   └─ Análisis técnico - decisiones y comparativas

4. 📊 RESUMEN_EJECUTIVO.md (10 min)
   └─ Overview del proyecto y mejoras

5. 📑 INDICE_REFERENCIA_RAPIDA.md (Este archivo)
   └─ Referencia rápida de comandos y estructura

🔧 SCRIPTS Y CONFIGURACIÓN:

6. ⭐ setup-docker.sh (EJECUTAR)
   └─ Script automatizado - 6 fases en 1 comando

7. docker-compose.yml (Referencia)
   └─ Orquestación de servicios (ya existía)

8. Dockerfile (Referencia)
   └─ Imagen PHP 8.2-FPM (ya existía)

9. .env.docker (Referencia)
   └─ Variables de entorno para Docker (ya existía)
```

---

## 🚀 Comandos Principales

### Setup Inicial

```bash
# ⚡ OPCIÓN 1: Completamente automático (RECOMENDADO)
./setup-docker.sh

# 🛠️ OPCIÓN 2: Manual paso a paso
docker compose down -v && rm -rf vendor
docker compose up -d --build && sleep 10
docker compose run --rm assets
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

### Verificación

```bash
# Ver estado de contenedores
docker compose ps

# Ver logs en tiempo real
docker compose logs -f

# Ver logs específicos
docker compose logs laravel
docker compose logs db
docker compose logs nginx
```

### Trabajo Diario

```bash
# Acceder a consola PHP
docker compose exec laravel bash

# Ejecutar Tinker (REPL)
docker compose exec laravel php artisan tinker

# Ejecutar tests
docker compose exec laravel php artisan test

# Ver estado de migraciones
docker compose exec laravel php artisan migrate:status

# Ejecutar seeder específico
docker compose exec laravel php artisan db:seed --class=NombreSeeder

# Limpiar caches
docker compose exec laravel php artisan cache:clear
docker compose exec laravel php artisan config:clear
```

### Control de Contenedores

```bash
# Parar sin eliminar datos
docker compose stop

# Reiniciar
docker compose start

# Parar y eliminar TODO (incluyendo BD!)
docker compose down -v

# Reconstruir sin cache
docker compose build --no-cache

# Reiniciar un servicio
docker compose restart laravel
```

### Base de Datos

```bash
# Acceder a PostgreSQL
docker compose exec db psql -U postgres -d mercado_agricola

# Exportar BD
docker compose exec db pg_dump -U postgres mercado_agricola > backup.sql

# Restaurar BD
docker compose exec -T db psql -U postgres mercado_agricola < backup.sql

# Ver conexiones activas
docker compose exec db psql -U postgres -d mercado_agricola -c "SELECT datname, count(*) FROM pg_stat_activity GROUP BY datname;"
```

---

## 🔍 Troubleshooting Rápido

| Problema | Comando | Nota |
|----------|---------|------|
| Puerto 8081 en uso | `docker compose down -v` | Libera puerto y volumen |
| PostgreSQL no arranca | `docker compose logs db` | Ver detalles del error |
| Composer falla | `rm -rf vendor && docker compose build` | Reinstala dependencias |
| APP_KEY falta | `docker compose exec laravel php artisan key:generate --force` | Genera clave |
| Assets no compilados | `docker compose run --rm assets` | Ejecuta Vite manualmente |
| Permisos storage | `docker compose exec laravel chmod -R 777 storage bootstrap/cache` | Ajusta permisos |
| Ver todos los logs | `docker compose logs | tail -100` | Últimas 100 líneas |
| Reiniciar Laravel | `docker compose restart laravel` | Solo reinicia PHP |
| Limpiar todo | `docker compose down -v && rm -rf vendor` | Nuclear option |

---

## 📊 URLs de Acceso

| Servicio | URL Local | URL Codespaces | Puerto |
|----------|-----------|----------------|--------|
| **Aplicación** | http://localhost:8081 | Botón Ports | 8081 |
| **PostgreSQL** | localhost:5432 | Forwarded | 5432 |
| **Laravel CLI** | docker compose exec | N/A | 9000 |
| **Nginx** | localhost:8081 | Botón Ports | 8081 |

---

## 🏗️ Estructura de Servicios

```
┌─────────────────────────────────────────────────────────┐
│ Docker Compose (Network: agrovida-net)                  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  NGINX Container                                        │
│  ├─ Escucha: :80 (expuesto como :8081)                │
│  └─ Proxea a: laravel:9000 (PHP-FPM)                 │
│                                                         │
│  LARAVEL Container (PHP-FPM)                           │
│  ├─ Imagen: php:8.2-fpm                               │
│  ├─ Extensiones: PDO, PostgreSQL, ZIP                 │
│  ├─ Volumen: . :/var/www (código fuente)             │
│  └─ Entrypoint: entrypoint.sh (instalación automática)│
│                                                         │
│  PostgreSQL Container                                  │
│  ├─ Versión: 16                                        │
│  ├─ Puerto: 5432                                       │
│  ├─ Volumen: db-data:/var/lib/postgresql/data        │
│  └─ Credenciales: postgres/root                       │
│                                                         │
│  ASSETS Container (Perfil opcional)                    │
│  ├─ Imagen: node:22-alpine                            │
│  ├─ Ejecuta: npm install + npm run build              │
│  └─ Genera: public/build/ (archivos compilados)       │
│                                                         │
│  NGROK Container (Perfil opcional)                     │
│  ├─ Imagen: ngrok/ngrok:latest                        │
│  ├─ Proxea: nginx:80 (para HTTPS/GPS)                │
│  └─ Puerto: 4040 (dashboard)                          │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## 📝 Variables de Entorno Importantes

### .env.docker (Para Docker)

```env
# Aplicación
APP_NAME=AgroVida
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8081

# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=db                    # ← IMPORTANTE: nombre del contenedor
DB_PORT=5432
DB_DATABASE=mercado_agricola
DB_USERNAME=postgres
DB_PASSWORD=root              # ⚠️ CAMBIAR EN PRODUCCIÓN

# Session & Cache
SESSION_DRIVER=file           # Para desarrollo
CACHE_STORE=array             # Simplificado para dev

# Startup automático (opcional)
RUN_STARTUP_TASKS=false       # true = ejecuta migraciones
RUN_SEEDERS=false             # true = ejecuta seeders
```

### En .env (local, NO Docker)

```env
# Las mismas variables, pero con:
DB_HOST=127.0.0.1            # localhost en lugar de 'db'
APP_URL=http://localhost:8000 # puerto local distinto
```

---

## 🎯 Checklist de Verificación

### Después de `./setup-docker.sh`

- [ ] `docker compose ps` → 3 contenedores en estado "Up"
  - [ ] agrovida-laravel (Up)
  - [ ] agrovida-nginx (Up)
  - [ ] agrovida-db (Up)

- [ ] `docker compose logs` → Sin errores críticos

- [ ] Acceder a `http://localhost:8081` → Página carga correctamente

- [ ] `docker compose exec laravel php artisan tinker` → Funciona REPL

- [ ] Base de datos conectada:
  - [ ] `docker compose exec db psql -U postgres -d mercado_agricola -c "SELECT COUNT(*) FROM users;"`

- [ ] Storage link funcionando:
  - [ ] `ls -la storage/app/public` → debe ser link simbólico

---

## 🧠 Conceptos Clave

### Docker Compose Network (agrovida-net)

```
Los contenedores se comunican entre sí por nombre de servicio:
- Laravel accede a BD: DB_HOST=db (no localhost)
- Nginx accede a Laravel: fastcgi_pass agrovida-laravel:9000
- Todos en la red agrovida-net (bridge)
```

### Volumen db-data

```
Persiste entre "docker compose down -v" SOLO si no usas -v
Si ejecutas "docker compose down -v", se elimina el volumen
Alternativa: respaldar con "docker volume inspect"
```

### Entrypoint.sh - Ejecución Automática

```
1. Crear .env desde .env.example (si falta)
2. Instalar Composer (si vendor/ no existe)
3. Generar APP_KEY (si falta)
4. Opcional: migraciones (si RUN_STARTUP_TASKS=true)
5. Opcional: seeders (si RUN_SEEDERS=true)
6. Iniciar PHP-FPM
```

### Perfiles Docker Compose

```yaml
# Perfil: assets (solo ejecutar cuando sea necesario)
docker compose run --rm assets
# Perfil: ngrok (para HTTPS/GPS móvil)
docker compose up -d --profile tunnel
```

---

## 📚 Archivos de Configuración Clave

```
Dockerfile
├─ Base: php:8.2-fpm
├─ Extensiones: PDO, PostgreSQL, ZIP
└─ Entrypoint: /usr/local/bin/entrypoint.sh

docker-compose.yml
├─ 5 servicios: laravel, nginx, db, assets, ngrok
├─ Red: agrovida-net (bridge)
├─ Volúmenes: db-data (nombrado), . (bind)
└─ Puertos: 8081 (nginx), 5432 (postgres), 4040 (ngrok)

nginx.conf
├─ Escucha: 0.0.0.0:80
├─ Root: /var/www/public
└─ Proxea FastCGI a: agrovida-laravel:9000

entrypoint.sh
├─ Fase 1: Crear .env
├─ Fase 2: Instalar Composer
├─ Fase 3: Generar APP_KEY
└─ Fase 4: Tareas opcionales + iniciar FPM

.env.docker
├─ Variables para Docker (DB_HOST=db)
├─ Debug habilitado
└─ Seeders/migraciones: controlables
```

---

## 🚨 Errores Comunes y Soluciones

### Error: "Port 8081 already in use"
```bash
# Solución rápida
docker compose down -v
docker compose up -d --build
```

### Error: "FATAL: Ident authentication failed"
```bash
# PostgreSQL no se conecta
docker compose logs db
# Puede ser que el volumen esté corrupto
docker compose down -v
docker compose up -d
```

### Error: "Could not find driver"
```bash
# Falta extensión PHP
# El Dockerfile debería tenerla, pero si no:
docker compose build --no-cache
docker compose up -d --build
```

### Error: "Module not found" (assets)
```bash
# Vite/npm falla
docker compose run --rm assets npm install
docker compose run --rm assets npm run build
```

### Error: "Timeout waiting for PostgreSQL"
```bash
# BD tarda mucho en iniciar
# Aumenta el sleep en setup-docker.sh o espera manualmente
docker compose logs db
sleep 30
docker compose exec laravel php artisan migrate:fresh --seed
```

---

## 💡 Tips Profesionales

### 1. Monitoreo en Tiempo Real
```bash
# Terminal 1: Logs
docker compose logs -f

# Terminal 2: Trabajo normal
docker compose exec laravel bash
```

### 2. Desarrollo sin Reiniciar
```bash
# Los cambios en código se reflejan automáticamente
# (porque está en volumen bind)
```

### 3. Respaldar Estado Antes de Experimentar
```bash
docker compose exec db pg_dump -U postgres mercado_agricola > backup_before_experiment.sql
# ... hacer cambios ...
# Si algo se daña:
docker compose exec -T db psql -U postgres mercado_agricola < backup_before_experiment.sql
```

### 4. Limpiar Periódicamente
```bash
# Eliminar imágenes no usadas
docker image prune -a

# Eliminar volúmenes no usados
docker volume prune

# Eliminar contenedores detenidos
docker container prune
```

### 5. Usar .env.local para Overrides
```env
# .env.local (Git-ignored)
# Sobrescribe variables de .env.docker
APP_DEBUG=false
DB_PASSWORD=mi_password_especial
```

---

## 📖 Documentación Oficial

- **Laravel**: https://laravel.com/docs/
- **PostgreSQL**: https://www.postgresql.org/docs/
- **Docker**: https://docs.docker.com/
- **Docker Compose**: https://docs.docker.com/compose/
- **Vite**: https://vitejs.dev/
- **Tailwind**: https://tailwindcss.com/

---

## 📞 Resumen de Contactos

| Necesidad | Archivo a Consultar |
|-----------|-------------------|
| "¿Cómo empiezo?" | QUICK_START.md |
| "¿Qué comando ejecuto?" | Este documento |
| "¿Por qué falló?" | PLAN_DOCKER_CODESPACES.md (troubleshooting) |
| "¿Por qué se eligió X tecnología?" | TECH_DECISIONS.md |
| "Resumen general" | RESUMEN_EJECUTIVO.md |

---

## ⏱️ Tiempos de Referencia

```
Lectura de QUICK_START.md:    5 min
Ejecución de setup-docker.sh:  10 min (automático)
Setup manual paso a paso:       15 min
Total primer levantamiento:     ~15-20 min

Después (reiniciar ya levantado):
  docker compose start           1 min
  docker compose logs -f         (en tiempo real)
```

---

## ✅ Estado Actual

- ✅ Proyecto analizado
- ✅ Plan completado
- ✅ Documentación generada (5 niveles)
- ✅ Script automatizado creado
- ✅ Configuraciones validadas
- ✅ **Listo para usar**

---

**Última actualización**: 2026-06-13  
**Versión**: 1.0  
**Estado**: ✅ Producción-ready
