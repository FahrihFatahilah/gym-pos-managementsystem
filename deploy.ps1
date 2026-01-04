# Gym & POS System - Docker Deployment Script (Windows)
# Usage: .\deploy.ps1 [environment]

param(
    [string]$Environment = "production"
)

$APP_NAME = "gym-pos-system"
$DOCKER_IMAGE = "$APP_NAME:latest"

Write-Host "🚀 Starting deployment for $APP_NAME in $Environment environment..." -ForegroundColor Green

# Check if Docker is running
try {
    docker info | Out-Null
} catch {
    Write-Host "❌ Docker is not running. Please start Docker Desktop first." -ForegroundColor Red
    exit 1
}

# Stop existing containers
Write-Host "🛑 Stopping existing containers..." -ForegroundColor Yellow
docker-compose down

# Remove old images (optional)
Write-Host "🧹 Cleaning up old images..." -ForegroundColor Yellow
docker image prune -f

# Build new image
Write-Host "🔨 Building Docker image..." -ForegroundColor Blue
docker-compose build --no-cache

# Start services
Write-Host "🚀 Starting services..." -ForegroundColor Green
docker-compose up -d

# Wait for services to be ready
Write-Host "⏳ Waiting for services to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Check if application is running
Write-Host "🔍 Checking application status..." -ForegroundColor Blue
try {
    $response = Invoke-WebRequest -Uri "http://localhost:80" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Application is running successfully!" -ForegroundColor Green
        Write-Host "🌐 Access your application at: http://localhost" -ForegroundColor Cyan
    }
} catch {
    Write-Host "❌ Application failed to start. Checking logs..." -ForegroundColor Red
    docker-compose logs app
    exit 1
}

# Show running containers
Write-Host "📋 Running containers:" -ForegroundColor Blue
docker-compose ps

Write-Host "🎉 Deployment completed successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Useful commands:" -ForegroundColor Yellow
Write-Host "  View logs: docker-compose logs -f app"
Write-Host "  Stop app:  docker-compose down"
Write-Host "  Restart:   docker-compose restart"
Write-Host "  Shell:     docker-compose exec app bash"