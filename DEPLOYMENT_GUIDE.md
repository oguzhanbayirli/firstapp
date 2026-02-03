# Deployment & Production Optimization Guide

## Pre-Deployment Checklist

### 1. Environment Configuration
Create a production `.env` file with optimizations:

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your_database
DB_USERNAME=db_user
DB_PASSWORD=secure_password

# Cache (use Redis in production)
CACHE_DRIVER=redis
CACHE_PREFIX=firstapp_

# Session (use Redis in production)
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Queue (for async jobs)
QUEUE_CONNECTION=redis

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (configure for your provider)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Storage
FILESYSTEM_DISK=public
APP_URL=https://your-domain.com
```

### 2. Database Optimization

```bash
# Run all migrations including indexes
php artisan migrate

# Seed initial data if needed
php artisan db:seed

# Verify indexes were created
php artisan tinker
>>> DB::select("SHOW INDEX FROM posts");
>>> DB::select("SHOW INDEX FROM follow");
>>> DB::select("SHOW INDEX FROM users");
```

### 3. Asset Compilation

```bash
# Install dependencies
npm install

# Build assets for production (minified)
npm run build

# Verify manifest.json was generated
ls -la public/build/manifest.json
```

### 4. Clear and Cache Configuration

```bash
# Clear Laravel caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cache optimized autoloader
composer install --optimize-autoloader --no-dev
```

## Nginx Configuration (Recommended)

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    # SSL Certificates
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    # Performance optimizations
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    keepalive_timeout 65;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1000;
    gzip_proxied any;
    gzip_types text/plain text/css text/xml text/javascript 
               application/x-javascript application/xml+rss 
               application/json application/javascript;

    root /var/www/firstapp/public;
    index index.php index.html;

    # Cache static assets
    location ~* ^/build/.*\.(js|css|svg|gif|jpg|jpeg|png|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Cache storage files
    location ~* ^/storage/avatars/.*\.(jpg|jpeg|png|gif)$ {
        expires 30d;
        add_header Cache-Control "public, max-age=2592000";
    }

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # PHP-FPM optimizations
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

## Apache Configuration

```apache
<VirtualHost *:443>
    ServerName your-domain.com
    ServerAdmin admin@your-domain.com
    DocumentRoot /var/www/firstapp/public

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    SSLCertificateKeyFile /path/to/key.pem

    # Enable mod_rewrite
    <Directory /var/www/firstapp/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted

        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>

    # Cache static assets
    <FilesMatch "\.(jpg|jpeg|png|gif|css|js|svg|woff|woff2)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>

    # Security headers
    Header always set Strict-Transport-Security "max-age=31536000"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"

    # Gzip compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml 
                              text/css text/javascript application/javascript
    </IfModule>

    # Disable directory listing
    Options -Indexes
</VirtualHost>

<VirtualHost *:80>
    ServerName your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>
```

## PHP-FPM Configuration

```ini
[www]
; Connection pool
listen = /run/php-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

; Process pool
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 2
pm.max_spare_servers = 10
pm.max_requests = 500
pm.max_requests_grace_period = 30s

; Performance
max_execution_time = 30
memory_limit = 128M
post_max_size = 50M
upload_max_filesize = 50M

; Security
disable_functions = exec,passthru,shell_exec,system
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php-fpm.log
```

## Redis Configuration (for Caching & Sessions)

```bash
# Install Redis
sudo apt-get install redis-server

# Configure Redis for production
# Edit /etc/redis/redis.conf

# Set memory management
maxmemory 256mb
maxmemory-policy allkeys-lru

# Enable persistence
save 900 1
save 300 10
save 60 10000

# Start Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Verify connection from Laravel
php artisan tinker
>>> redis()->ping()
=> "PONG"
```

## Database Optimization (MySQL/MariaDB)

```sql
-- Create database and user
CREATE DATABASE firstapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'firstapp_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON firstapp.* TO 'firstapp_user'@'localhost';
FLUSH PRIVILEGES;

-- Optimize my.cnf for Laravel
-- Edit /etc/mysql/my.cnf or /etc/mysql/conf.d/mysql.cnf

[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Performance tuning
max_connections = 100
max_allowed_packet = 256M
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 0  # Disabled in modern MySQL
query_cache_type = 0
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

[mysql]
default-character-set = utf8mb4
```

## Automated Backups

```bash
# Create backup script: /usr/local/bin/backup-firstapp.sh

#!/bin/bash

BACKUP_DIR="/backups/firstapp"
MYSQL_USER="firstapp_user"
MYSQL_PASS="strong_password"
MYSQL_DB="firstapp"
APP_DIR="/var/www/firstapp"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $MYSQL_USER -p$MYSQL_PASS $MYSQL_DB | \
    gzip > $BACKUP_DIR/db-$(date +%Y%m%d-%H%M%S).sql.gz

# Files backup (selective)
tar czf $BACKUP_DIR/files-$(date +%Y%m%d-%H%M%S).tar.gz \
    -C $APP_DIR app config database public/storage \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache'

# Keep only last 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete

# Schedule in crontab (runs daily at 2 AM)
# 0 2 * * * /usr/local/bin/backup-firstapp.sh

chmod +x /usr/local/bin/backup-firstapp.sh
```

## Monitoring & Logging

```php
// Configure logging in config/logging.php
'channels' => [
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'warning'),
    ],
    'performance' => [
        'driver' => 'single',
        'path' => storage_path('logs/performance.log'),
        'level' => 'info',
    ],
];
```

## Security Hardening

```bash
# File permissions
sudo chown -R www-data:www-data /var/www/firstapp
sudo chmod -R 755 /var/www/firstapp
sudo chmod -R 755 /var/www/firstapp/public
sudo chmod -R 775 /var/www/firstapp/storage
sudo chmod -R 775 /var/www/firstapp/bootstrap/cache

# Disable unnecessary services
sudo systemctl disable apache2  # if using Nginx
sudo systemctl disable postfix  # if not needed

# Update system
sudo apt-get update
sudo apt-get upgrade -y

# Install fail2ban for DDoS protection
sudo apt-get install fail2ban
sudo systemctl enable fail2ban
```

## Post-Deployment Verification

```bash
# 1. Test application
curl https://your-domain.com
# Should return 200 with homepage

# 2. Test API endpoint
curl https://your-domain.com/search/test
# Should return JSON search results

# 3. Check error logs
tail -f /var/log/firstapp.log

# 4. Monitor performance
php artisan tinker
DB::enableQueryLog();
// ... test queries ...
dd(DB::getQueryLog());

# 5. Verify caching works
redis-cli ping
# Should return PONG

# 6. Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
=> PDOConnection {...}
```

## Performance Monitoring Tools

### 1. New Relic
```bash
# Install PHP agent
wget https://download.newrelic.com/install/newrelic-php/newrelic.so
# Configure in php.ini and dashboard

# View metrics at https://one.newrelic.com
```

### 2. Datadog
```bash
# Install agent
DD_AGENT_MAJOR_VERSION=7 DD_API_KEY=your_key DD_SITE=datadoghq.com \
  bash -c "$(curl -L https://s3.amazonaws.com/...)"
```

### 3. Laravel Telescope (Development Only)
```bash
# Already installed in development
# Do NOT enable in production
# Remove from production dependencies if accidentally included
```

## Performance Targets (Production)

| Metric | Target | Alert |
|--------|--------|-------|
| Page Load Time | < 500ms | > 1000ms |
| API Response Time | < 200ms | > 500ms |
| Database Query Time | < 100ms | > 250ms |
| Cache Hit Rate | > 85% | < 60% |
| Error Rate | < 0.1% | > 1% |
| Memory Usage | < 128MB/request | > 256MB/request |
| CPU Usage | < 40% | > 80% |

## Rollback Plan

```bash
# Keep previous version
cp -r /var/www/firstapp /var/www/firstapp-backup-$(date +%Y%m%d-%H%M%S)

# Quick rollback if issues occur
rm -rf /var/www/firstapp
mv /var/www/firstapp-backup-YYYY-MM-DD-HH-MM-SS /var/www/firstapp

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

## Maintenance Windows

```bash
# Enable maintenance mode during deployments
php artisan down --secret="deployment123"
# Access site at: https://your-domain.com/deployment123

# Deploy and run migrations
# ...

# Bring site back online
php artisan up
```

## References

- Laravel Deployment: https://laravel.com/docs/11.x/deployment
- Server Security: https://laravel.com/docs/11.x/installation#server-requirements
- Environment Configuration: https://laravel.com/docs/11.x/configuration#environment-configuration
- Production Optimization: https://laravel.com/docs/11.x/optimization
