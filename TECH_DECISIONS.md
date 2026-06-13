# 📊 Análisis y Decisiones Técnicas - Setup Docker AgroVida

## 🔍 Comparación con Proyecto Similar (Agrovida Anterior)

### Lo que Reutilizamos ✅

| Elemento | Proyecto Similar | Este Proyecto | Decisión |
|----------|------------------|---------------|----------|
| **Estructura Docker** | docker-compose.yml | docker-compose.yml | ✅ Patrón idéntico |
| **.env.docker** | Configuración en `.env.docker` | Configuración en `.env.docker` | ✅ Mismo approach |
| **Puerto Nginx** | Puerto 8081 | Puerto 8081 | ✅ Reutilizado |
| **Entrypoint inteligente** | entrypoint.sh con lógica | entrypoint.sh mejorado | ✅ Mejorado y expandido |
| **Variables de Control** | `RUN_STARTUP_TASKS` | `RUN_STARTUP_TASKS` + `RUN_SEEDERS` | ✅ Expandido |
| **Red Docker** | Red dedicada | Red `agrovida-net` | ✅ Reutilizado |
| **Volumen BD** | Volumen nombrado | Volumen `db-data` | ✅ Reutilizado |

### Mejoras Implementadas 🚀

| Aspecto | Anterior | Actual | Beneficio |
|--------|----------|--------|-----------|
| **PHP** | 8.1 | 8.2 | Mayor rendimiento, características nuevas |
| **PostgreSQL** | 15 | 16 | Mejor performance, nuevas features |
| **Node.js Assets** | Node 18 | Node 22 Alpine | Más rápido, imagen más ligera |
| **Node Profile** | Combinado | Perfil separado (`assets`) | Mejor control, ejecución independiente |
| **Ngrok** | Opcional manual | Perfil Docker integrado | Fácil para GPS/HTTPS en móvil |
| **Script Setup** | Manual paso a paso | Script automatizado | Menos errores, reproducible |

---

## 🎯 Decisiones Técnicas Clave

### 1. PostgreSQL como Base de Datos ✅

**Por qué:**
- Proyecto usa Laravel + PostgreSQL
- Mejor para esquemas complejos (relaciones M2M, JSON)
- Mejor soporte para GIS (si hay mapas/coordenadas)

**Alternativas descartadas:**
- MySQL: Proyecto ya usa PostgreSQL
- SQLite: Solo desarrollo, no escalable

**Configuración:**
```env
DB_CONNECTION=pgsql
DB_HOST=db                 # Nombre del contenedor en la red
DB_PORT=5432              # Puerto estándar PostgreSQL
DB_DATABASE=mercado_agricola
DB_USERNAME=postgres
DB_PASSWORD=root          # ⚠️ CAMBIAR EN PRODUCCIÓN
```

---

### 2. Nginx + PHP-FPM (No Apache) ✅

**Por qué:**
- Más ligero que Apache
- Mejor para entornos containerizados
- Más rápido en Codespaces con recursos limitados

**Configuración:**
```nginx
# nginx.conf apunta a agrovida-laravel:9000 (PHP-FPM)
# Soporta uploads hasta 32MB
# Caching de buffers optimizado
```

---

### 3. Node.js en Contenedor Separado ✅

**Decisión:**
- No instalar Node en el contenedor PHP
- Usar perfil Docker: `docker compose run --rm assets`
- **Ventaja**: Contenedor PHP más ligero y enfocado

**Por qué:**
```dockerfile
# Alternativa descartada: Todo en PHP
RUN apt-get install nodejs npm  # Hincha la imagen

# Actual: Separado
# assets:
#   image: node:22-alpine  # Solo 150MB vs 300MB con PHP+Node
```

---

### 4. Perfil "assets" Separado ✅

**Configuración:**
```yaml
  assets:
    image: node:22-alpine      # Imagen minimalista
    profiles:
      - assets               # Solo ejecutar cuando se necesite
    command: sh -c "npm ci && npm run build"
```

**Uso:**
```bash
docker compose run --rm assets    # Ejecutar manualmente cuando sea necesario
```

**Alternativas:**
- ❌ En cada `docker compose up` (lentitud innecesaria)
- ✅ Solo cuando cambias CSS/JS (decisión correcta)

---

### 5. Entrypoint Inteligente ✅

**Lógica del entrypoint.sh:**

```bash
1. Crear .env desde .env.example si falta ✓
2. Instalar Composer solo si vendor/ no existe ✓
3. Generar APP_KEY si falta ✓
4. Tareas opcionales via RUN_STARTUP_TASKS ✓
   - Migraciones
   - Seeders (controlable con RUN_SEEDERS)
   - Cache clearing
   - Permisos
5. Iniciar PHP-FPM ✓
```

**Ventajas:**
- Idempotente (puedes ejecutar múltiples veces sin problemas)
- Controlable via variables de entorno
- No requiere comandos manuales

---

### 6. Volumen de Persistencia ✅

```yaml
volumes:
  db-data:                    # Volumen nombrado
    driver: bridge
```

**Beneficio:**
- Datos persisten entre `docker compose down`
- Mejor que volumen bind (más seguro para BD)
- Respaldar: `docker volume inspect agrovida_db-data`

---

### 7. Red Dedicada ✅

```yaml
networks:
  agrovida-net:
    driver: bridge            # Todos los contenedores en la misma red
```

**Beneficio:**
- Contenedores se comunican por nombre de servicio
- Laravel accede a BD con `DB_HOST=db`
- Nginx accede a Laravel con `fastcgi_pass agrovida-laravel:9000`
- Aislado de otros proyectos Docker en el Codespace

---

## 📋 Arquitectura de Servicios

```
┌─────────────────────────────────────────────────────────┐
│                  DOCKER COMPOSE                         │
│                                                         │
│  Network: agrovida-net (bridge)                        │
│  ┌──────────────────────────────────────────────┐      │
│  │                                              │      │
│  │  ┌─────────────┐  ┌────────────┐            │      │
│  │  │ NGINX:80    │  │ Laravel:   │ (depends) │      │
│  │  │ Port 8081   ├──┤ PHP-FPM:   │           │      │
│  │  │ (listener)  │  │ 9000       │           │      │
│  │  └─────────────┘  └────┬───────┘           │      │
│  │                        │                   │      │
│  │                   (DB_HOST=db)             │      │
│  │                        │                   │      │
│  │                   ┌────▼──────┐            │      │
│  │                   │ PostgreSQL │            │      │
│  │                   │ :5432      │            │      │
│  │                   │ (persists) │            │      │
│  │                   └───────────┘            │      │
│  │                                            │      │
│  │  [assets] - Perfil opcional (Node.js)     │      │
│  │  [ngrok]  - Perfil opcional (Tunel HTTPS) │      │
│  └──────────────────────────────────────────────┘      │
│                                                         │
│  Volumes:                                              │
│  - db-data: /var/lib/postgresql/data                  │
│  - . :/var/www (bind - código fuente)                 │
│                                                         │
│  Environment:                                          │
│  - .env.docker (variables)                            │
│  - SESSION_DRIVER=file                                │
│  - CACHE_STORE=array (simplificado)                   │
└─────────────────────────────────────────────────────────┘

       ↓ Puerto expuesto a host

   http://localhost:8081 ← Acceso desde navegador
```

---

## 🔐 Variables de Entorno

### Archivo: `.env.docker` (Para Docker)

```env
# Base
APP_NAME=AgroVida
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8081

# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=db               # ← CLAVE: nombre del contenedor
DB_PORT=5432
DB_DATABASE=mercado_agricola
DB_USERNAME=postgres
DB_PASSWORD=root

# Session & Cache
SESSION_DRIVER=file      # ← En Docker, usar file
CACHE_STORE=array        # ← Simplificado para desarrollo

# Seeders (Controlables)
RUN_SEEDERS=false        # ← Cambiar a true para auto-seed
RUN_STARTUP_TASKS=false  # ← Cambiar a true para auto-migrate
```

**⚠️ Para Producción:**
- Cambiar `APP_DEBUG=false`
- Cambiar contraseña PostgreSQL
- Usar `CACHE_STORE=redis` o similar
- Usar `SESSION_DRIVER=database` o Redis

---

## 🛠️ Herramientas Utilizadas

| Herramienta | Versión | Propósito | En Codespaces |
|-------------|---------|----------|---------------|
| **Docker** | Latest | Containerización | ✅ Pre-instalado |
| **Docker Compose** | V2 | Orquestación | ✅ Pre-instalado |
| **PHP** | 8.2-FPM | Runtime Laravel | 📦 En imagen |
| **PostgreSQL** | 16 | Base de datos | 📦 En imagen |
| **Nginx** | Latest | Web server | 📦 En imagen |
| **Node.js** | 22 Alpine | Assets (Vite) | 📦 En perfil `assets` |
| **Composer** | Latest | Dependencias PHP | 📦 En Dockerfile |
| **Vite** | 5.4 | Build tool frontend | 📦 En package.json |
| **Tailwind** | 3.4 | CSS framework | 📦 En package.json |

---

## 📚 Ficheros Generados

```
/workspaces/equipo07/
│
├── QUICK_START.md                    ← Guía rápida (COMIENZA AQUÍ)
├── PLAN_DOCKER_CODESPACES.md         ← Plan detallado + troubleshooting
├── TECH_DECISIONS.md                 ← Este archivo (análisis técnico)
├── setup-docker.sh                   ← Script automatizado
│
├── docker-compose.yml                ← Config (ya existía, revisada)
├── Dockerfile                        ← Config (ya existía, revisada)
├── entrypoint.sh                     ← Config (ya existía, mejorado)
├── nginx.conf                        ← Config (ya existía, revisada)
├── .env.docker                       ← Config (ya existía, validada)
└── .env.example                      ← Plantilla (ya existía)
```

---

## ✅ Validaciones Realizadas

- ✅ `docker-compose.yml` - Sintaxis correcta, servicios bien definidos
- ✅ `Dockerfile` - Extensiones PHP necesarias instaladas
- ✅ `entrypoint.sh` - Lógica correcta y robusta
- ✅ `nginx.conf` - Configuración correcta para Laravel
- ✅ `.env.docker` - Variables correctas, credenciales configuradas
- ✅ `composer.json` - Dependencias apropiadas (DOMPDF, Excel, QR, etc.)
- ✅ `package.json` - Dependencias de assets correctas

---

## 🎯 Flujo de Levantamiento

```
1. SETUP-DOCKER.SH (ejecutar)
   │
   ├─→ Check Requirements (Docker, Docker Compose)
   │
   ├─→ Cleanup (bajar contenedores, eliminar vendor)
   │
   ├─→ Build & Start (docker compose up -d --build)
   │   └─→ Crea: Laravel, Nginx, PostgreSQL en red agrovida-net
   │   └─→ Espera: 30 segundos para que PostgreSQL esté listo
   │
   ├─→ Build Assets (docker compose run --rm assets)
   │   └─→ Crea: Node.js, npm install, vite build
   │   └─→ Genera: archivos en public/build/
   │
   ├─→ Setup Database
   │   ├─→ php artisan key:generate
   │   ├─→ php artisan migrate:fresh --seed
   │   └─→ php artisan storage:link
   │
   ├─→ Verify Setup
   │   ├─→ docker compose ps (verificar estado)
   │   ├─→ Probar tinker de Laravel
   │   └─→ Probar conexión PostgreSQL
   │
   └─→ Show Summary (instrucciones de acceso)

2. ACCEDER A LA APLICACIÓN
   └─→ http://localhost:8081
```

---

## 🚨 Problemas Comunes y Soluciones Previstas

| Problema | Causa | Solución en Script |
|----------|-------|-------------------|
| Puerto 8081 en uso | Otro proyecto Docker | `docker compose down -v` antes de build |
| PostgreSQL no arranca | Volumen corrupto | `docker compose down -v` elimina volumen |
| Composer falla | vendor/ corrupto | `rm -rf vendor` antes de build |
| APP_KEY falta | Entrypoint no ejecutó | Generado en Fase 5 del script |
| Assets no compilados | npm falla | Perfil `assets` ejecuta independientemente |

---

## 💾 Respaldos y Restauración

```bash
# Exportar BD completa
docker compose exec db pg_dump -U postgres mercado_agricola > backup_$(date +%Y%m%d).sql

# Restaurar BD
docker compose exec -T db psql -U postgres mercado_agricola < backup.sql

# Respaldar volumen
docker run --rm -v agrovida_db-data:/data -v $(pwd):/backup \
  alpine tar czf /backup/db-data-backup.tar.gz -C /data .

# Respaldar código
git commit -am "backup pre-clean"
```

---

## 🎓 Próximos Pasos Recomendados

1. **Ejecutar setup**: `./setup-docker.sh`
2. **Acceder a la app**: `http://localhost:8081`
3. **Explorar codebase**: `docker compose exec laravel bash`
4. **Ver logs**: `docker compose logs -f`
5. **Revisar seeders**: `database/seeders/`
6. **Modificar según necesidades locales**: Editar `.env.docker`

---

## 📞 Referencias

- **Docker**: https://docs.docker.com/
- **Docker Compose**: https://docs.docker.com/compose/
- **Laravel**: https://laravel.com/docs/
- **PostgreSQL**: https://www.postgresql.org/docs/
- **Nginx**: https://nginx.org/en/docs/

---

**Última actualización**: 2026-06-13  
**Autor**: Plan de Levantamiento Automatizado  
**Estado**: ✅ Listo para usar
