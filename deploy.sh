#!/bin/bash

# Gym & POS System - Docker Deployment Script
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT=${1:-production}
APP_NAME="gym-pos-system"
DOCKER_IMAGE="$APP_NAME:latest"

echo "🚀 Starting deployment for $APP_NAME in $ENVIRONMENT environment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker compose down || true

# Remove old images (optional)
echo "🧹 Cleaning up old images..."
docker image prune -f

# Build new image
echo "🔨 Building Docker image..."
docker compose build --no-cache

# Start services
echo "🚀 Starting services..."
docker compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Check if application is running
echo "🔍 Checking application status..."
if curl -f http://localhost:80 > /dev/null 2>&1; then
    echo "✅ Application is running successfully!"
    echo "🌐 Access your application at: http://localhost"
else
    echo "❌ Application failed to start. Checking logs..."
    docker compose logs app
    exit 1
fi

# Show running containers
echo "📋 Running containers:"
docker compose ps

echo "🎉 Deployment completed successfully!"
echo ""
echo "📝 Useful commands:"
echo "  View logs: docker compose logs -f app"
echo "  Stop app:  docker compose down"
echo "  Restart:   docker compose restart"
echo "  Shell:     docker compose exec app bash"