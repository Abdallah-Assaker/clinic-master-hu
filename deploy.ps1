# ============================================
# Simple Deployment Script (Non-Production)
# ============================================
# Builds new image, stops old containers, starts new ones
# ============================================

$ErrorActionPreference = "Stop"

Write-Host "[START] Deploying updated application..." -ForegroundColor Cyan

# Clear any environment variables that might interfere
Remove-Item Env:\APP_PORT -ErrorAction SilentlyContinue
Remove-Item Env:\COMPOSE_PROJECT_NAME -ErrorAction SilentlyContinue

# Step 1: Build new images
Write-Host "[BUILD] Building new Docker images..." -ForegroundColor Yellow
docker compose build # --no-cache

if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Build failed!" -ForegroundColor Red
    exit 1
}

Write-Host "[SUCCESS] Images built successfully!" -ForegroundColor Green

# Step 2: Stop old containers
Write-Host "[STOP] Stopping old containers..." -ForegroundColor Yellow
docker compose down

# Step 3: Start new containers and wait for health checks
Write-Host "[START] Starting new containers..." -ForegroundColor Yellow
docker compose up -d --wait --wait-timeout 180

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "============================================================" -ForegroundColor Green
    Write-Host "    *** DEPLOYMENT COMPLETED! ***" -ForegroundColor Green
    Write-Host "============================================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "[SUCCESS] Containers have been updated with the new version" -ForegroundColor Green
    Write-Host "[SUCCESS] All containers are healthy and running" -ForegroundColor Green
    Write-Host "[INFO] Application is available at http://localhost:8000" -ForegroundColor Cyan
    Write-Host ""
    
    # Show running containers
    docker ps --format "table {{.Names}}\t{{.Ports}}\t{{.Status}}" | Out-String | Write-Host
} else {
    Write-Host ""
    Write-Host "[ERROR] Deployment failed!" -ForegroundColor Red
    Write-Host "[INFO] Check logs with: docker compose logs" -ForegroundColor Yellow
    exit 1
}
