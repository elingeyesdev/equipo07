param(
    [string]$Token = $env:NGROK_AUTHTOKEN
)

$ErrorActionPreference = "Stop"

if ([string]::IsNullOrWhiteSpace($Token)) {
    throw "Falta el token de ngrok. Ejecuta: .\scripts\start-ngrok.ps1 -Token 'TU_TOKEN'"
}

if ($Token -match '^(TU_TOKEN|TU_TOKEN_REAL|TU_TOKEN_DE_NGROK)$') {
    throw "Debes reemplazar '$Token' por el authtoken real copiado desde https://dashboard.ngrok.com/get-started/your-authtoken"
}

$env:NGROK_AUTHTOKEN = $Token

Write-Host "Iniciando la aplicacion y el tunel HTTPS..." -ForegroundColor Cyan
docker compose up -d nginx laravel db
docker compose --profile tunnel up -d ngrok

$publicUrl = $null

for ($i = 1; $i -le 20; $i++) {
    Start-Sleep -Seconds 2

    try {
        $response = Invoke-RestMethod -Uri "http://localhost:4040/api/tunnels" -TimeoutSec 3
        $publicUrl = $response.tunnels |
            Where-Object { $_.proto -eq "https" } |
            Select-Object -First 1 -ExpandProperty public_url

        if ($publicUrl) {
            break
        }
    } catch {
        # El agente de ngrok todavia esta iniciando.
    }
}

if (-not $publicUrl) {
    Write-Host ""
    Write-Host "ngrok no pudo crear el tunel. Ultimas lineas del registro:" -ForegroundColor Red
    docker compose --profile tunnel logs --tail=20 ngrok
    throw "Revisa el authtoken y vuelve a ejecutar el comando."
}

Write-Host ""
Write-Host "URL publica: $publicUrl" -ForegroundColor Green
Write-Host "Acceso transporte: $publicUrl/transporte" -ForegroundColor Green
Write-Host "Panel local ngrok: http://localhost:4040" -ForegroundColor DarkGray
