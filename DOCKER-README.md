# 🐳 Docker Deployment - Gym & POS System

Panduan lengkap untuk deploy aplikasi Gym & POS System menggunakan Docker.

## 📋 Prerequisites

- Docker Desktop (Windows/Mac) atau Docker Engine (Linux)
- Docker Compose
- Git (untuk clone repository)

## 🚀 Quick Start

### Windows (PowerShell)
```powershell
# Clone repository
git clone <repository-url>
cd gym-pos-system

# Run deployment script
.\deploy.ps1
```

### Linux/Mac (Bash)
```bash
# Clone repository
git clone <repository-url>
cd gym-pos-system

# Make script executable
chmod +x deploy.sh

# Run deployment script
./deploy.sh
```

### Manual Deployment
```bash
# Build and start containers
docker-compose up -d --build

# Check status
docker-compose ps

# View logs
docker-compose logs -f app
```

## 🔧 Configuration

### Environment Variables
Edit `docker-compose.yml` untuk mengubah konfigurasi:

```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
  - DB_CONNECTION=sqlite
  - DB_DATABASE=/var/www/database/database.sqlite
```

### Port Configuration
Default port: `80`

Untuk mengubah port:
```yaml
ports:
  - "8080:80"  # Akses via http://localhost:8080
```

## 📁 Volume Mapping

Data persistent disimpan di:
- `./storage` - File uploads, logs, cache
- `./database` - SQLite database
- `./public/storage` - Public storage link

## 🛠 Management Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Restart services
docker-compose restart

# View logs
docker-compose logs -f app

# Access container shell
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
```

## 🔍 Troubleshooting

### Application not accessible
```bash
# Check container status
docker-compose ps

# Check logs
docker-compose logs app

# Check nginx logs
docker-compose exec app tail -f /var/log/nginx/error.log
```

### Database issues
```bash
# Reset database
docker-compose exec app php artisan migrate:fresh --seed

# Check database file
docker-compose exec app ls -la /var/www/database/
```

### Permission issues
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

## 🔄 Updates

```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
docker-compose down
docker-compose up -d --build
```

## 📊 Monitoring

### Health Check
```bash
# Check if app is running
curl http://localhost/login

# Check container resources
docker stats gym-pos-app
```

### Backup Database
```bash
# Backup SQLite database
docker-compose exec app cp /var/www/database/database.sqlite /var/www/storage/backup-$(date +%Y%m%d).sqlite
```

## 🌐 Production Deployment

### With SSL/HTTPS
1. Setup reverse proxy (nginx/traefik)
2. Configure SSL certificates
3. Update environment variables

### With External Database
```yaml
environment:
  - DB_CONNECTION=mysql
  - DB_HOST=mysql-server
  - DB_DATABASE=gym_pos
  - DB_USERNAME=user
  - DB_PASSWORD=password
```

## 📝 Notes

- Default admin user akan dibuat otomatis saat first run
- SQLite database digunakan secara default
- Semua static assets di-cache untuk performa optimal
- PWA support sudah terintegrasi

## 🆘 Support

Jika mengalami masalah:
1. Check logs: `docker-compose logs app`
2. Restart services: `docker-compose restart`
3. Rebuild image: `docker-compose up -d --build --force-recreate`