#!/bin/bash
set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funciones de logging
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

log_step() {
    echo -e "\n${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}\n"
}

# Verificar requisitos
check_requirements() {
    log_step "Fase 1: Verificando Requisitos"
    
    if ! command -v docker &> /dev/null; then
        log_error "Docker no está instalado"
        exit 1
    fi
    log_success "Docker disponible: $(docker --version)"
    
    if ! command -v docker-compose &> /dev/null; then
        log_error "Docker Compose no está instalado"
        exit 1
    fi
    log_success "Docker Compose disponible: $(docker compose --version)"
}

# Limpiar estado anterior
cleanup() {
    log_step "Fase 2: Limpiando Estado Anterior"
    
    log_info "Bajando contenedores anteriores..."
    docker compose down -v 2>/dev/null || true
    log_success "Contenedores bajados"
    
    if [ -d "vendor" ]; then
        log_info "Eliminando vendor/ para forzar reinstalación..."
        rm -rf vendor
        log_success "vendor/ eliminado"
    fi
}

# Build y arranque
build_and_start() {
    log_step "Fase 3: Construyendo y Arrancando Contenedores"
    
    log_info "Construyendo imágenes y iniciando servicios..."
    docker compose up -d --build
    
    log_success "Contenedores iniciados"
    
    log_info "Esperando a que PostgreSQL esté listo (30 segundos)..."
    sleep 30
    log_success "PostgreSQL debería estar listo"
}

# Compilar assets
build_assets() {
    log_step "Fase 4: Compilando Assets Frontend"
    
    log_info "Instalando dependencias npm y compilando con Vite..."
    docker compose run --rm assets
    log_success "Assets compilados exitosamente"
}

# Inicializar base de datos
setup_database() {
    log_step "Fase 5: Inicializando Base de Datos"
    
    log_info "Generando APP_KEY..."
    docker compose exec laravel php artisan key:generate --force
    log_success "APP_KEY generado"
    
    log_info "Ejecutando migraciones y seeders..."
    docker compose exec laravel php artisan migrate:fresh --seed
    log_success "Migraciones y seeders completados"
    
    log_info "Creando storage link..."
    docker compose exec laravel php artisan storage:link
    log_success "Storage link creado"
}

# Verificación final
verify_setup() {
    log_step "Fase 6: Verificación Final"
    
    log_info "Estado de contenedores:"
    docker compose ps
    
    log_info "\nVerificando conectividad a la aplicación..."
    if docker compose exec -T laravel php artisan tinker --execute="echo 'Laravel está funcionando';" &>/dev/null; then
        log_success "Laravel está funcionando correctamente"
    else
        log_warning "No se pudo verificar Laravel, pero podría estar bien"
    fi
    
    # Verificar base de datos
    log_info "Verificando conexión a PostgreSQL..."
    if docker compose exec -T db psql -U postgres -d mercado_agricola -c "SELECT 1;" &>/dev/null; then
        log_success "PostgreSQL está accesible"
    else
        log_error "No se pudo conectar a PostgreSQL"
        return 1
    fi
}

# Mostrar resumen final
show_summary() {
    log_step "✨ SETUP COMPLETADO EXITOSAMENTE"
    
    echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${GREEN}🎉 AgroVida está listo para usar!${NC}"
    echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}\n"
    
    echo -e "📍 Acceso a la aplicación:"
    echo -e "   ${BLUE}Local: http://localhost:8081${NC}"
    echo -e "   ${BLUE}Codespaces: Usa el port forwarding (botón Ports)${NC}\n"
    
    echo -e "📊 Servicios activos:"
    echo -e "   ${BLUE}Laravel (PHP-FPM):${NC} http://localhost:9000"
    echo -e "   ${BLUE}Nginx (Web Server):${NC} http://localhost:8081"
    echo -e "   ${BLUE}PostgreSQL:${NC} localhost:5432${NC}\n"
    
    echo -e "📚 Comandos útiles:"
    echo -e "   ${BLUE}Ver logs:${NC} docker compose logs -f"
    echo -e "   ${BLUE}Acceder a consola:${NC} docker compose exec laravel bash"
    echo -e "   ${BLUE}Tinker (REPL):${NC} docker compose exec laravel php artisan tinker"
    echo -e "   ${BLUE}Ver estado:${NC} docker compose ps${NC}\n"
    
    echo -e "📖 Documentación:"
    echo -e "   ${BLUE}Ver PLAN_DOCKER_CODESPACES.md para más detalles${NC}\n"
}

# Manejo de errores
handle_error() {
    log_error "Algo salió mal en la fase anterior"
    log_info "Revisa los logs con: docker compose logs"
    exit 1
}

trap handle_error ERR

# Main
main() {
    clear
    echo -e "${BLUE}"
    cat << "EOF"
╔══════════════════════════════════════════════════════════════════╗
║                                                                  ║
║           🚀 SETUP AUTOMÁTICO - AgroVida con Docker             ║
║                                                                  ║
║  Este script levantará completamente la aplicación en Docker    ║
║  en tu Codespace de forma automatizada y segura.                ║
║                                                                  ║
╚══════════════════════════════════════════════════════════════════╝
EOF
    echo -e "${NC}\n"
    
    check_requirements
    cleanup
    build_and_start
    build_assets
    setup_database
    verify_setup
    show_summary
}

# Ejecutar
main "$@"
