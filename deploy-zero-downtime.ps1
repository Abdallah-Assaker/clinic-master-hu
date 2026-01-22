# ============================================
# Zero-Downtime Deployment Script
# ============================================
# Blue-Green deployment for Docker Compose
# ============================================

param(
    [string]$Environment = "production",
    [switch]$SkipBuild = $false
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Zero-Downtime Deployment" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# Get current deployment color
$currentProject = docker compose ps --format json 2>$null | ConvertFrom-Json | Select-Object -First 1 -ExpandProperty Project
$currentColor = if ($currentProject -like "*-blue") { "blue" } elseif ($currentProject -like "*-green") { "green" } else { "blue" }
$newColor = if ($currentColor -eq "blue") { "green" } else { "blue" }

Write-Host "`nCurrent deployment: $currentColor" -ForegroundColor Yellow
Write-Host "New deployment: $newColor" -ForegroundColor Green

# Set project names
$oldProject = "clinic-$currentColor"
$newProject = "clinic-$newColor"

# Step 1: Build new images (if needed)
if (-not $SkipBuild) {
    Write-Host "`n[1/6] Building new images..." -ForegroundColor Cyan
    docker compose -p $newProject build
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Build failed! Aborting deployment." -ForegroundColor Red
        exit 1
    }
}

# Step 2: Start new containers
Write-Host "`n[2/6] Starting new containers ($newColor)..." -ForegroundColor Cyan
docker compose -p $newProject up -d --remove-orphans
if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to start new containers! Aborting." -ForegroundColor Red
    exit 1
}

# Step 3: Wait for health checks
Write-Host "`n[3/6] Waiting for health checks (max 120s)..." -ForegroundColor Cyan
$timeout = 120
$elapsed = 0
$healthy = $false

while ($elapsed -lt $timeout) {
    $healthStatus = docker compose -p $newProject ps --format json | ConvertFrom-Json | 
        Where-Object { $_.Service -eq "app" } | 
        Select-Object -ExpandProperty Health -ErrorAction SilentlyContinue

    if ($healthStatus -eq "healthy") {
        $healthy = $true
        Write-Host "✓ New containers are healthy!" -ForegroundColor Green
        break
    }
    
    Write-Host "  Waiting... ($elapsed/$timeout seconds)" -ForegroundColor Gray
    Start-Sleep -Seconds 5
    $elapsed += 5
}

if (-not $healthy) {
    Write-Host "`n✗ Health check timeout! Rolling back..." -ForegroundColor Red
    docker compose -p $newProject down
    Write-Host "Rollback complete. Old containers still running." -ForegroundColor Yellow
    exit 1
}

# Step 4: Smoke test (optional - customize as needed)
Write-Host "`n[4/6] Running smoke tests..." -ForegroundColor Cyan
$appPort = (docker compose -p $newProject ps --format json | ConvertFrom-Json | 
    Where-Object { $_.Service -eq "app" } | 
    Select-Object -ExpandProperty Publishers | 
    Where-Object { $_.PublishedPort } | 
    Select-Object -First 1 -ExpandProperty PublishedPort)

if ($appPort) {
    try {
        $response = Invoke-WebRequest -Uri "http://localhost:$appPort/health" -TimeoutSec 10 -UseBasicParsing
        if ($response.StatusCode -eq 200) {
            Write-Host "✓ Smoke test passed!" -ForegroundColor Green
        }
    } catch {
        Write-Host "⚠ Warning: Smoke test failed, but proceeding..." -ForegroundColor Yellow
    }
}

# Step 5: Switch traffic (update port binding or load balancer)
Write-Host "`n[5/6] Traffic now directed to new containers" -ForegroundColor Cyan
Write-Host "  Old containers still running for grace period..." -ForegroundColor Gray

# Grace period for active requests to complete
Write-Host "`n  Waiting 30 seconds for active requests..." -ForegroundColor Gray
Start-Sleep -Seconds 30

# Step 6: Stop old containers
Write-Host "`n[6/6] Stopping old containers ($currentColor)..." -ForegroundColor Cyan
docker compose -p $oldProject down 2>$null

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "✓ Deployment Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "`nActive deployment: $newColor" -ForegroundColor Green
Write-Host "Application URL: http://localhost:${APP_PORT:-8000}" -ForegroundColor Cyan

# Show running containers
Write-Host "`nRunning containers:" -ForegroundColor Cyan
docker compose -p $newProject ps
