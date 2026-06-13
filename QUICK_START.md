# 🚀 Quick Start - AgroVida en Codespaces

## ⚡ Levantamiento Rápido (Opción 1: Automático)

```bash
./setup-docker.sh
```

Esto ejecuta **todas** las fases automáticamente. Espera ~5-10 minutos. ☕

---

## 🎯 Levantamiento Manual (Opción 2: Paso a Paso)

### 1️⃣ Preparación (1 min)
```bash
docker compose down -v 2>/dev/null || true
rm -rf vendor
```

### 2️⃣ Build y arranque (3-5 min)
```bash
docker compose up -d --build
```

### 3️⃣ Compilar assets (1-2 min)
```bash
docker compose run --rm assets
```

### 4️⃣ Migraciones y BD (2-3 min)
```bash
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link
```

### ✅ Verificar
```bash
docker compose ps
# Todos los contenedores deben estar en "Up"
```

### 🌐 Acceder
- **URL**: `http://localhost:8081`
- En Codespaces: usar el botón "Ports" en VS Code

---

## 📊 Estado y Logs

```bash
# Ver todos los contenedores
docker compose ps

# Ver logs en tiempo real
docker compose logs -f

# Logs específicos
docker compose logs -f laravel      # Solo Laravel
docker compose logs -f db           # Solo PostgreSQL
docker compose logs -f nginx        # Solo Nginx
```

---

## 🔧 Comandos Diarios

```bash
# Acceder a consola PHP
docker compose exec laravel bash

# Ejecutar tinker (REPL de Laravel)
docker compose exec laravel php artisan tinker

# Ejecutar tests
docker compose exec laravel php artisan test

# Limpiar caches
docker compose exec laravel php artisan cache:clear
docker compose exec laravel php artisan config:clear

# Ver migraciones
docker compose exec laravel php artisan migrate:status

# Seed específico
docker compose exec laravel php artisan db:seed --class=NombreSeeder

# Detener (sin eliminar datos)
docker compose stop

# Volver a arrancar
docker compose start

# Detener y eliminar TODO (including BD!)
docker compose down -v
```

---

## 🐛 Troubleshooting Rápido

| Problema | Solución |
|----------|----------|
| **Puerto 8081 en uso** | `docker compose down && docker compose up -d` |
| **PostgreSQL no arranca** | `docker compose logs db` y `docker compose down -v` |
| **Composer falla** | `rm -rf vendor && docker compose up -d` |
| **Assets no compilados** | `docker compose run --rm assets` |
| **Permisos de storage** | `docker compose exec laravel chmod -R 777 storage bootstrap/cache` |
| **APP_KEY falta** | `docker compose exec laravel php artisan key:generate --force` |

---

## 📋 Checklist de Primer Setup

- [ ] `docker --version` (verifica que Docker existe)
- [ ] `docker compose up -d --build` (arranca servicios)
- [ ] `docker compose run --rm assets` (compila Vite)
- [ ] `docker compose ps` (verifica 3 contenedores en Up)
- [ ] `docker compose exec laravel php artisan migrate:fresh --seed`
- [ ] Acceder a `http://localhost:8081`
- [ ] Ver que carga la página sin errores

---

## 📁 Archivos Importantes

```
/workspaces/equipo07/
├── docker-compose.yml      ← Orquestación de servicios
├── Dockerfile              ← Imagen Laravel/PHP
├── .env.docker             ← Variables de entorno (usa esta!)
├── .env.example            ← Plantilla de .env
├── entrypoint.sh           ← Script que ejecuta el contenedor
├── nginx.conf              ← Configuración del web server
├── setup-docker.sh         ← Script automático de setup 👈 USA ESTO
├── PLAN_DOCKER_CODESPACES.md ← Plan detallado
└── README.md               ← Documentación general
```

---

## 🎓 Explicación Rápida de la Arquitectura

```
┌─────────────────────────────────────────────┐
│           Tu Navegador (localhost:8081)     │
└────────────────┬────────────────────────────┘
                 │
        ┌────────▼────────┐
        │ NGINX Container │ (Web server)
        │ Port: 8081      │
        └────────┬────────┘
                 │
        ┌────────▼────────────────┐
        │ Laravel Container       │
        │ (PHP-FPM)               │
        │ DB_HOST: db             │◄──── Los 3 en misma red
        └────────┬────────────────┘
                 │
        ┌────────▼──────────────┐
        │ PostgreSQL Container  │
        │ (Base de Datos)       │
        │ Puerto: 5432          │
        └───────────────────────┘
```

---

## 💡 Tips Profesionales

1. **Monitoreo en tiempo real**: Abre 2 terminales
   ```bash
   # Terminal 1: Logs
   docker compose logs -f
   
   # Terminal 2: Trabaja normalmente
   docker compose exec laravel bash
   ```

2. **Desarrollo sin parar**: Los cambios en código se reflejan automáticamente porque el directorio está montado en volumen

3. **Backup de BD**:
   ```bash
   docker compose exec db pg_dump -U postgres mercado_agricola > backup.sql
   ```

4. **Restaurar BD**:
   ```bash
   docker compose exec -T db psql -U postgres mercado_agricola < backup.sql
   ```

5. **Acceso directo a PostgreSQL**:
   ```bash
   docker compose exec db psql -U postgres -d mercado_agricola
   ```

---

## 🆘 Soporte y Más Info

- **Plan completo**: Ver `PLAN_DOCKER_CODESPACES.md`
- **Estructura del proyecto**: Ver `README.md`
- **Errores de Docker**: `docker compose logs`
- **Errores de Laravel**: `docker compose logs laravel`

---

**¿Listo? ¡Ejecuta `./setup-docker.sh` y espera!** ☕✨
