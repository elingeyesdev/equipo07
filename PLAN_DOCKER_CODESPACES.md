# Plan de Levantamiento del Proyecto AgroVida en Codespaces con Docker

## 📋 Análisis Actual del Proyecto

### Stack Tecnológico
- **Backend**: Laravel 12 + PHP 8.2-FPM
- **Frontend**: Vite + Tailwind CSS + PostCSS
- **Base de Datos**: PostgreSQL 16
- **Servidor Web**: Nginx (puerto 8081)
- **Orchestración**: Docker Compose
- **Dependencias Extras**: 
  - `barryvdh/laravel-dompdf` - Generación de PDFs
  - `maatwebsite/excel` - Manejo de Excel
  - `laravel/tinker` - REPL interactivo
  - `html5-qrcode` y `qrcode` - QR codes
  - Tailwind CSS y Autoprefixer para estilos

### Servicios Docker
1. **laravel** - Contenedor PHP-FPM (build customizado)
2. **nginx** - Servidor web (puerto 8081)
3. **db** - PostgreSQL 16 (puerto 5432)
4. **assets** - Node.js 22 Alpine para compilar activos (perfil opcional)
5. **ngrok** - Tunel HTTPS (perfil opcional, para GPS móvil)

### Configuración Actual ✅
- `.env.docker` ya configurado correctamente
- `docker-compose.yml` optimizado
- `Dockerfile` con extensiones PHP necesarias
- `entrypoint.sh` con lógica de inicialización inteligente
- `nginx.conf` correctamente configurado

---

## 🚀 Plan de Levantamiento en Codespaces

### **Fase 1: Preparación (5 min)**

#### 1.1 Verificar Docker en Codespaces
```bash
docker --version
docker compose --version
```
✅ Docker ya disponible en Codespaces

#### 1.2 Limpiar estado anterior (si existe)
```bash
docker compose down -v  # Baja contenedores y elimina volúmenes
rm -rf vendor           # Fuerza reinstalación de Composer
```

---

### **Fase 2: Construcción de Contenedores (10-15 min)**

#### 2.1 Build y arranque
```bash
docker compose up -d --build
```

**Qué sucede automáticamente:**
- ✅ Construye imagen PHP con extensiones (PDO, PostgreSQL, ZIP)
- ✅ Descarga imagen Nginx
- ✅ Descarga imagen PostgreSQL 16
- ✅ Crea red `agrovida-net`
- ✅ Crea volumen `db-data` para persistencia
- ✅ El `entrypoint.sh` en el contenedor Laravel:
  - Copia `.env.example` → `.env` si no existe
  - Instala dependencias Composer (`composer install`)
  - Genera `APP_KEY` automáticamente
  - Inicia PHP-FPM

#### 2.2 Compilar assets frontend
```bash
docker compose run --rm assets
```

**Qué sucede:**
- ✅ Instala dependencias npm (`npm ci`)
- ✅ Compila con Vite (`npm run build`)
- ✅ Genera archivos en `public/build/`

---

### **Fase 3: Inicialización de Base de Datos (5-10 min)**

#### 3.1 Generar APP_KEY (si no lo hizo el entrypoint)
```bash
docker compose exec laravel php artisan key:generate --force
```

#### 3.2 Ejecutar migraciones y seeders
```bash
docker compose exec laravel php artisan migrate:fresh --seed
```

**Opciones según necesidad:**
```bash
# Solo migraciones sin seeders
docker compose exec laravel php artisan migrate

# Migraciones desde cero con seeders
docker compose exec laravel php artisan migrate:fresh --seed

# Solo seeders (si ya hay BD)
docker compose exec laravel php artisan db:seed
```

#### 3.3 Crear link de almacenamiento
```bash
docker compose exec laravel php artisan storage:link
```

---

### **Fase 4: Verificación y Acceso (2 min)**

#### 4.1 Verificar contenedores activos
```bash
docker compose ps
```

Debe mostrar 3 contenedores en estado **Up**:
- `agrovida-laravel`
- `agrovida-nginx`
- `agrovida-db`

#### 4.2 Verificar logs
```bash
docker compose logs laravel | tail -20    # Últimas líneas de Laravel
docker compose logs db | tail -20          # Últimas líneas de PostgreSQL
docker compose logs nginx | tail -10       # Últimas líneas de Nginx
```

#### 4.3 Acceder a la aplicación
- **Local**: `http://localhost:8081`
- **Codespaces**: Usar el port forwarding automático (botón Ports en VS Code)

---

## 🔧 Tareas Automatizadas Opcionales

### Opción A: Automatizar TODO en un script

Crea [setup-docker.sh](setup-docker.sh):
```bash
#!/bin/bash
set -e

echo "🐳 Iniciando levantamiento de AgroVida..."

echo "📦 Bajando contenedores anteriores..."
docker compose down -v 2>/dev/null || true
rm -rf vendor

echo "🔨 Construyendo contenedores..."
docker compose up -d --build

echo "⏳ Esperando que PostgreSQL esté listo..."
sleep 10

echo "📚 Compilando assets..."
docker compose run --rm assets

echo "🔑 Generando APP_KEY..."
docker compose exec laravel php artisan key:generate --force

echo "🗄️  Ejecutando migraciones y seeders..."
docker compose exec laravel php artisan migrate:fresh --seed

echo "🔗 Creando storage link..."
docker compose exec laravel php artisan storage:link

echo "✅ Proyecto levantado exitosamente"
echo "🌐 Acceder en: http://localhost:8081"
```

Ejecutar:
```bash
chmod +x setup-docker.sh
./setup-docker.sh
```

### Opción B: Usar variables de entorno para automatizar

Modificar `.env.docker`:
```env
RUN_STARTUP_TASKS=true    # Ejecuta migraciones automáticamente
RUN_SEEDERS=true          # Ejecuta seeders
```

Luego solo:
```bash
docker compose up -d --build
docker compose run --rm assets
```

---

## 🐛 Troubleshooting

### Problema: Puerto 8081 ya en uso
```bash
# Ver qué usa el puerto
lsof -i :8081

# Cambiar puerto en docker-compose.yml
# De:   ports: - "8081:80"
# A:    ports: - "8082:80"
```

### Problema: PostgreSQL no arranca
```bash
docker compose logs db
docker compose down -v  # Elimina volumen corrupto
docker compose up -d
```

### Problema: Composer falla dentro del contenedor
```bash
rm -rf vendor
docker compose exec laravel composer install --no-interaction --prefer-dist
```

### Problema: Assets no compilados
```bash
docker compose run --rm assets
# O manualmente:
docker compose exec assets npm run build
```

### Problema: Permisos de storage
```bash
docker compose exec laravel chmod -R 777 storage bootstrap/cache
```

### Ver logs completos
```bash
docker compose logs -f                # Todos los servicios
docker compose logs -f laravel        # Solo Laravel
docker compose logs -f db             # Solo PostgreSQL
```

---

## 📊 Comandos Útiles del Día a Día

```bash
# Arrastrar contenedores
docker compose up -d

# Ver estado
docker compose ps

# Ver logs en tiempo real
docker compose logs -f

# Ejecutar comando Artisan
docker compose exec laravel php artisan tinker
docker compose exec laravel php artisan migrate

# Acceder a la consola interactiva
docker compose exec laravel bash

# Detener sin eliminar
docker compose stop

# Detener y eliminar todo
docker compose down

# Eliminar también volúmenes (borra BD)
docker compose down -v

# Reconstruir imagen
docker compose build --no-cache

# Reiniciar servicio
docker compose restart laravel
```

---

## 📝 Checklist de Levantamiento

- [ ] Docker y Docker Compose funcionando (`docker --version`)
- [ ] Ejecutar `docker compose up -d --build`
- [ ] Esperar ~30 segundos a que PostgreSQL esté listo
- [ ] Ejecutar `docker compose run --rm assets`
- [ ] Ejecutar migraciones: `docker compose exec laravel php artisan migrate:fresh --seed`
- [ ] Crear storage link: `docker compose exec laravel php artisan storage:link`
- [ ] Verificar contenedores: `docker compose ps` (todos en Up)
- [ ] Acceder a http://localhost:8081
- [ ] Login con credenciales de seeder (revisar `database/seeders/`)

---

## 🎯 Resumen Rápido (Para Próximas Veces)

```bash
# Setup inicial completo
docker compose up -d --build && sleep 10 && docker compose run --rm assets && docker compose exec laravel php artisan migrate:fresh --seed && docker compose exec laravel php artisan storage:link

# Acceder después
docker compose up -d
docker compose logs -f
```

---

## 📌 Notas Importantes para Codespaces

1. **Puerto 8081 automaticamente forwardeado**: VS Code en Codespaces detecta automáticamente el puerto 8081 y lo expone públicamente.

2. **Límites de Codespaces**: 
   - Storage: 32 GB
   - RAM: Suficiente para Laravel + PostgreSQL
   - CPU: Compartido pero adecuado para desarrollo

3. **Persistencia**: Los volúmenes Docker persisten entre reinicios de Codespaces (mientras el espacio no se elimine).

4. **Base de datos**: Si necesitas exportar/importar:
   ```bash
   # Exportar
   docker compose exec db pg_dump -U postgres mercado_agricola > dump.sql
   
   # Importar
   docker compose exec -T db psql -U postgres mercado_agricola < dump.sql
   ```

---

## 🎓 Diferencias con tu Proyecto Similar

✅ **Reutilizamos:**
- Estructura de servicios Docker similar
- Configuración de variables en `.env.docker`
- Puerto 8081 para Nginx
- Entrypoint.sh con lógica inteligente
- Variables `RUN_STARTUP_TASKS` y `RUN_SEEDERS`

✅ **Mejoras en este proyecto:**
- PostgreSQL 16 (en lugar de versión anterior)
- PHP 8.2-FPM más moderno
- Ngrok integrado para HTTPS/GPS móvil
- Perfil de assets con Node.js 22 Alpine (más ligero)
- Red dedicada `agrovida-net`
- Volumen nombrado para persistencia de BD

---

## ✨ Próximos Pasos Recomendados

1. Ejecutar el plan **Fase 1 → Fase 4** en orden
2. Una vez funcionando, explorar rutas en `routes/web.php` y `routes/api.php`
3. Para desarrollo, usar `docker compose logs -f` para monitoreo en tiempo real
4. Para testing: `docker compose exec laravel php artisan test`
