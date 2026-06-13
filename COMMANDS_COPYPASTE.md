# 🎯 Copy-Paste Commands - AgroVida Docker

Todos los comandos listos para copy-paste. Simplemente copia y pega en tu terminal.

---

## ⚡ INICIO RÁPIDO

### Opción 1: Completamente Automático
```bash
./setup-docker.sh
```

### Opción 2: Setup Manual
```bash
docker compose down -v && rm -rf vendor && docker compose up -d --build && sleep 10 && docker compose run --rm assets && docker compose exec laravel php artisan migrate:fresh --seed && docker compose logs -f
```

### Opción 3: Paso a Paso
```bash
# Paso 1: Limpiar
docker compose down -v
rm -rf vendor

# Paso 2: Build y arranque
docker compose up -d --build

# Paso 3: Esperar a PostgreSQL
sleep 10

# Paso 4: Compilar assets
docker compose run --rm assets

# Paso 5: Migraciones
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan storage:link

# Paso 6: Ver logs
docker compose logs -f
```

---

## 📊 COMANDOS DE ESTADO

### Ver contenedores
```bash
docker compose ps
```

### Ver logs
```bash
# Todos los servicios
docker compose logs -f

# Solo Laravel
docker compose logs -f laravel

# Solo PostgreSQL
docker compose logs -f db

# Solo Nginx
docker compose logs -f nginx
```

### Ver últimas líneas de logs
```bash
docker compose logs | tail -50
docker compose logs laravel | tail -30
```

---

## 🔧 CONTROL DE SERVICIOS

### Arrancar
```bash
docker compose up -d
```

### Parar (sin eliminar datos)
```bash
docker compose stop
```

### Reiniciar
```bash
docker compose start
```

### Reiniciar servicios específicos
```bash
docker compose restart laravel
docker compose restart db
docker compose restart nginx
```

### Eliminar TODO (incluyendo BD!)
```bash
docker compose down -v
```

---

## 🗄️ BASE DE DATOS

### Acceder a PostgreSQL
```bash
docker compose exec db psql -U postgres -d mercado_agricola
```

### Ejecutar query SQL
```bash
docker compose exec db psql -U postgres -d mercado_agricola -c "SELECT COUNT(*) FROM users;"
```

### Ver todas las tablas
```bash
docker compose exec db psql -U postgres -d mercado_agricola -c "\dt"
```

### Exportar BD (backup)
```bash
docker compose exec db pg_dump -U postgres mercado_agricola > backup.sql
```

### Restaurar BD desde backup
```bash
docker compose exec -T db psql -U postgres mercado_agricola < backup.sql
```

### Limpiar BD (eliminar todo)
```bash
docker compose exec db psql -U postgres -d mercado_agricola -c "DROP SCHEMA public CASCADE; CREATE SCHEMA public;"
```

---

## 🛠️ LARAVEL ARTISAN

### Consola PHP (bash)
```bash
docker compose exec laravel bash
```

### Tinker (REPL interactivo)
```bash
docker compose exec laravel php artisan tinker
```

### Listar migraciones
```bash
docker compose exec laravel php artisan migrate:status
```

### Ejecutar migraciones
```bash
docker compose exec laravel php artisan migrate
docker compose exec laravel php artisan migrate:fresh
docker compose exec laravel php artisan migrate:fresh --seed
```

### Ejecutar seeders específicos
```bash
docker compose exec laravel php artisan db:seed --class=DatabaseSeeder
docker compose exec laravel php artisan db:seed --class=UserSeeder
```

### Ejecutar tests
```bash
docker compose exec laravel php artisan test
docker compose exec laravel php artisan test --filter=TestNombre
```

### Limpiar caches
```bash
docker compose exec laravel php artisan cache:clear
docker compose exec laravel php artisan config:clear
docker compose exec laravel php artisan route:clear
docker compose exec laravel php artisan view:clear
```

### Generar APP_KEY
```bash
docker compose exec laravel php artisan key:generate --force
```

### Ver rutas
```bash
docker compose exec laravel php artisan route:list
```

### Ver comandos disponibles
```bash
docker compose exec laravel php artisan list
```

---

## 🎨 ASSETS & VITE

### Compilar assets (build)
```bash
docker compose run --rm assets
```

### Ver estado de assets
```bash
docker compose logs assets
```

### Limpiar node_modules y reinstalar
```bash
docker compose run --rm assets npm ci
```

---

## 📦 DEPENDENCIAS

### Reinstalar Composer
```bash
rm -rf vendor
docker compose build --no-cache
docker compose up -d
```

### Instalar nuevo paquete Composer
```bash
docker compose exec laravel composer require paquete/nombre
```

### Actualizar dependencias Composer
```bash
docker compose exec laravel composer update
```

### Ver dependencias instaladas
```bash
docker compose exec laravel composer show
```

---

## 🐛 TROUBLESHOOTING

### Puerto 8081 en uso
```bash
# Ver qué ocupa el puerto
lsof -i :8081

# Liberar puerto
docker compose down -v
docker compose up -d --build
```

### PostgreSQL no arranca
```bash
# Ver logs
docker compose logs db

# Limpiar y reiniciar
docker compose down -v
docker compose up -d
sleep 30
```

### Composer falla
```bash
# Limpiar y reinstalar
rm -rf vendor
docker compose build --no-cache
docker compose up -d --build
```

### APP_KEY falta
```bash
docker compose exec laravel php artisan key:generate --force
```

### Assets no compilados
```bash
docker compose run --rm assets
```

### Permisos de storage
```bash
docker compose exec laravel chmod -R 777 storage bootstrap/cache
```

### Limpiar todo y empezar de nuevo
```bash
docker compose down -v
rm -rf vendor
rm -f .env
./setup-docker.sh
```

---

## 📈 MONITOREO EN TIEMPO REAL

### Abre DOS terminales

**Terminal 1: Monitoreo**
```bash
docker compose logs -f
```

**Terminal 2: Trabajo**
```bash
docker compose exec laravel bash
```

---

## 🔗 STORAGE LINK

### Crear symlink
```bash
docker compose exec laravel php artisan storage:link
```

### Verificar symlink
```bash
ls -la storage/app/public
```

---

## 🚀 NGROK (Para GPS/HTTPS en móvil)

### Activar ngrok
```bash
docker compose up -d --profile tunnel
```

### Ver logs de ngrok
```bash
docker compose logs -f ngrok
```

### Acceder a dashboard ngrok
```bash
http://localhost:4040
```

### Parar ngrok
```bash
docker compose down
```

---

## 🔐 VARIABLES DE ENTORNO

### Ver .env.docker
```bash
cat .env.docker
```

### Editar .env.docker
```bash
nano .env.docker
# o
vi .env.docker
```

### Cambiar variable
```bash
# Editar .env.docker con tu editor favorito
# Luego reiniciar
docker compose restart laravel
```

---

## 📊 INFORMACIÓN DEL SISTEMA

### Ver versiones
```bash
docker --version
docker compose --version
docker compose exec laravel php -v
docker compose exec db postgres --version
docker compose exec assets node -v
docker compose exec assets npm -v
```

### Ver tamaño de imágenes
```bash
docker images | grep agrovida
```

### Ver tamaño de volúmenes
```bash
docker volume ls | grep agrovida
```

### Espacio usado
```bash
docker system df
```

### Limpiar espacio (sin eliminar BD)
```bash
docker system prune
```

### Limpiar TODO (warning: elimina BD!)
```bash
docker system prune -a -v
```

---

## 🔄 REINICIO COMPLETO

### Si todo falla, opción nuclear:
```bash
# 1. Parar todo
docker compose down -v

# 2. Eliminar vendor
rm -rf vendor

# 3. Limpiar imágenes (opcional)
docker image prune -a

# 4. Reiniciar desde cero
./setup-docker.sh
```

---

## 📝 ALIASES ÚTILES (Agrega a tu ~/.bashrc o ~/.zshrc)

```bash
# AgroVida Docker shortcuts
alias agro-up="docker compose up -d --build"
alias agro-down="docker compose down"
alias agro-logs="docker compose logs -f"
alias agro-bash="docker compose exec laravel bash"
alias agro-artisan="docker compose exec laravel php artisan"
alias agro-ps="docker compose ps"
alias agro-db="docker compose exec db psql -U postgres -d mercado_agricola"
alias agro-test="docker compose exec laravel php artisan test"
alias agro-migrate="docker compose exec laravel php artisan migrate:fresh --seed"

# Usar:
# agro-up              (arrancar)
# agro-logs            (ver logs)
# agro-bash            (consola Laravel)
# agro-artisan tinker  (REPL)
```

---

## 💾 BACKUP COMPLETO

### Hacer backup de TODO
```bash
# BD
docker compose exec db pg_dump -U postgres mercado_agricola > backup_db.sql

# Código (git)
git commit -am "backup pre-clean"
git push

# Volumen Docker (opcional)
docker run --rm -v agrovida_db-data:/data -v $(pwd):/backup \
  alpine tar czf /backup/db-data-backup.tar.gz -C /data .
```

### Restaurar backup
```bash
# BD
docker compose exec -T db psql -U postgres mercado_agricola < backup_db.sql

# Código
git pull

# Volumen
docker run --rm -v agrovida_db-data:/data -v $(pwd):/backup \
  alpine tar xzf /backup/db-data-backup.tar.gz -C /data
```

---

## 🎯 FLUJO DIARIO

### Inicio del día
```bash
docker compose up -d
docker compose logs -f
```

### Trabajar
```bash
docker compose exec laravel bash
# o
docker compose exec laravel php artisan tinker
```

### Ver cambios
```bash
# Los archivos se reflejan automáticamente en volumen bind
# Si cambias CSS/JS, compilar:
docker compose run --rm assets
```

### Final del día
```bash
docker compose stop
# (sin eliminar datos)
```

---

## 🚀 CHEAT SHEET RÁPIDO

```
START:       docker compose up -d --build
LOGS:        docker compose logs -f
BASH:        docker compose exec laravel bash
ARTISAN:     docker compose exec laravel php artisan
DB:          docker compose exec db psql -U postgres -d mercado_agricola
TEST:        docker compose exec laravel php artisan test
MIGRATE:     docker compose exec laravel php artisan migrate:fresh --seed
STOP:        docker compose stop
DOWN:        docker compose down
DESTROY:     docker compose down -v && rm -rf vendor
```

---

**Última actualización**: 2026-06-13  
**Versión**: 1.0  
**Proyecto**: AgroVida
