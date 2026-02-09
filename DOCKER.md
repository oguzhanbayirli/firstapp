# Docker Deployment Guide

Bu doküman, Laravel uygulamasını Docker kullanarak deploy etme adımlarını içerir.

## 📦 Docker ile Deployment

### Gereksinimler
- Docker Engine 20.10+
- Docker Compose 2.0+

### Kurulum

#### 1. Environment Dosyasını Hazırlayın
```bash
cp .env.example .env
# .env dosyasını düzenleyin
```

#### 2. Docker İmajını Build Edin
```bash
docker-compose build
```

#### 3. Konteynerleri Başlatın
```bash
docker-compose up -d
```

#### 4. Uygulama Key'i Oluşturun
```bash
docker-compose exec app php artisan key:generate
```

#### 5. Migration'ları Çalıştırın
```bash
docker-compose exec app php artisan migrate --force
```

#### 6. Storage Link Oluşturun
```bash
docker-compose exec app php artisan storage:link
```

### Servisler

Docker Compose aşağıdaki servisleri başlatır:

- **app**: Ana Laravel uygulaması (Nginx + PHP-FPM)
  - Port: 8000
- **mysql**: MySQL 8.0 veritabanı
  - Port: 3306
- **redis**: Redis cache/session sunucusu
  - Port: 6379
- **queue**: Laravel queue worker
- **scheduler**: Laravel task scheduler

### Komutlar

```bash
# Tüm konteynerleri başlat
docker-compose up -d

# Konteynerleri durdur
docker-compose down

# Logları görüntüle
docker-compose logs -f

# Belirli bir servisin loglarını görüntüle
docker-compose logs -f app

# Konteyner içinde komut çalıştır
docker-compose exec app php artisan [command]

# Bash shell aç
docker-compose exec app bash

# Queue worker'ı yeniden başlat
docker-compose restart queue

# Tüm konteynerleri ve volume'leri sil
docker-compose down -v
```

### Development Mode

Development için `docker-compose.override.yml` oluşturun:

```yaml
version: '3.8'

services:
  app:
    volumes:
      - ./:/var/www/html
    environment:
      - APP_DEBUG=true
      - APP_ENV=local
```

### Production Deployment

#### 1. Environment Variables
Production `.env` dosyasını yapılandırın:
```env
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

#### 2. Build Production Image
```bash
docker-compose -f docker-compose.yml build --no-cache
```

#### 3. Start Services
```bash
docker-compose -f docker-compose.yml up -d
```

#### 4. Optimize Application
```bash
docker-compose exec app php artisan optimize
```

### Veritabanı Yedekleme

```bash
# Yedek oluştur
docker-compose exec mysql mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > backup.sql

# Yedekten geri yükle
docker-compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < backup.sql
```

### SSL/TLS (HTTPS)

Production için Nginx Proxy Manager veya Traefik kullanarak SSL terminasyon yapabilirsiniz.

#### Traefik Örneği

`docker-compose.traefik.yml`:
```yaml
version: '3.8'

services:
  app:
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.laravel.rule=Host(`yourdomain.com`)"
      - "traefik.http.routers.laravel.entrypoints=websecure"
      - "traefik.http.routers.laravel.tls.certresolver=letsencrypt"
    networks:
      - traefik
      - laravel

networks:
  traefik:
    external: true
```

### Monitoring

#### Health Check Ekle

`docker-compose.yml` içinde:
```yaml
services:
  app:
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/api/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s
```

Health check endpoint'i oluşturun:
```php
// routes/api.php
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});
```

### Troubleshooting

#### Konteynerleri Yeniden Başlatın
```bash
docker-compose restart
```

#### Cache'leri Temizleyin
```bash
docker-compose exec app php artisan optimize:clear
```

#### Permission Sorunları
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

#### Log'ları Kontrol Edin
```bash
# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Nginx logs
docker-compose logs -f app | grep nginx

# MySQL logs
docker-compose logs -f mysql
```

### Scaling

Queue worker'ları scale edin:
```bash
docker-compose up -d --scale queue=3
```

### Güvenlik

1. **Secrets Yönetimi**: Docker secrets veya environment variables kullanın
2. **Network İzolasyonu**: Servisleri özel network'te çalıştırın
3. **Read-only Filesystem**: Mümkün olduğunda read-only mount kullanın
4. **Non-root User**: Konteynerleri root olmayan kullanıcı ile çalıştırın

### CI/CD Entegrasyonu

#### GitHub Actions Örneği

`.github/workflows/deploy.yml`:
```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Copy files to server
        uses: appleboy/scp-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          source: "."
          target: "/var/www/app"
      
      - name: Deploy with Docker
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/app
            docker-compose down
            docker-compose build --no-cache
            docker-compose up -d
            docker-compose exec -T app php artisan migrate --force
            docker-compose exec -T app php artisan optimize
```

### Performance Tuning

#### PHP-FPM Optimization
`docker/php-fpm.conf`:
```ini
[www]
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

#### Nginx Optimization
Nginx konfigürasyonunda:
```nginx
worker_processes auto;
worker_rlimit_nofile 65535;

events {
    worker_connections 4096;
    use epoll;
}
```

## 🎯 Production Checklist

- [ ] `.env` dosyası production için yapılandırıldı
- [ ] `APP_DEBUG=false`
- [ ] SSL/TLS yapılandırıldı
- [ ] Database backups kuruldu
- [ ] Monitoring ve logging aktif
- [ ] Health checks yapılandırıldı
- [ ] Resource limits ayarlandı
- [ ] Security scanning yapıldı
- [ ] Load testing tamamlandı

## 📚 Daha Fazla Bilgi

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Docker Best Practices](https://laravel.com/docs/deployment)
