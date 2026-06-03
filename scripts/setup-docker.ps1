param(
    [switch]$SkipSeed
)

$ErrorActionPreference = "Stop"

function Run-Step {
    param(
        [string]$Title,
        [scriptblock]$Command
    )

    Write-Host ""
    Write-Host "==> $Title" -ForegroundColor Cyan
    & $Command
}

Run-Step "Construyendo y levantando contenedores" {
    docker compose up -d --build
}

Run-Step "Esperando PostgreSQL" {
    $ready = $false

    for ($i = 1; $i -le 30; $i++) {
        docker compose exec -T db pg_isready -U postgres -d mercado_agricola | Out-Null
        if ($LASTEXITCODE -eq 0) {
            $ready = $true
            break
        }

        Start-Sleep -Seconds 2
    }

    if (-not $ready) {
        throw "PostgreSQL no respondio a tiempo. Revisa: docker compose logs db"
    }
}

Run-Step "Compilando assets" {
    docker compose run --rm assets
}

Run-Step "Preparando Laravel" {
    docker compose exec -T laravel php artisan key:generate --force

    if ($SkipSeed) {
        docker compose exec -T laravel php artisan migrate --force
    } else {
        docker compose exec -T laravel php artisan migrate:fresh --seed --force
    }

    docker compose exec -T laravel php artisan storage:link
}

Run-Step "Estado final" {
    docker compose ps
}

Write-Host ""
Write-Host "Listo. Abre http://localhost:8081" -ForegroundColor Green
Write-Host "Admin: admin@agrovida.com / admin123"
