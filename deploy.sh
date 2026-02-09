#!/bin/bash

# Laravel Production Deployment Script
# Bu script, Laravel uygulamasını production ortamına deploy etmek için kullanılır

set -e

echo "🚀 Laravel Deployment Script Başlatılıyor..."
echo "================================================"

# Renkler
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Ortam değişkenleri
APP_DIR="/var/www/your-app"
PHP_VERSION="8.2"

# Fonksiyonlar
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# 1. Maintenance Mode
echo ""
print_info "Uygulama maintenance moduna alınıyor..."
php artisan down || true
print_success "Maintenance mode aktif"

# 2. Git Pull
echo ""
print_info "En son kod çekiliyor..."
git pull origin main
print_success "Kod güncellendi"

# 3. Composer Dependencies
echo ""
print_info "Composer bağımlılıkları yükleniyor..."
composer install --no-dev --optimize-autoloader --no-interaction
print_success "Composer bağımlılıkları yüklendi"

# 4. NPM Dependencies & Build
echo ""
print_info "NPM bağımlılıkları yükleniyor..."
npm ci
print_success "NPM bağımlılıkları yüklendi"

print_info "Asset'ler build ediliyor..."
npm run build
print_success "Asset'ler build edildi"

# 5. Database Migration
echo ""
print_info "Veritabanı migration'ları çalıştırılıyor..."
php artisan migrate --force
print_success "Migration'lar tamamlandı"

# 6. Clear Old Cache
echo ""
print_info "Eski cache'ler temizleniyor..."
php artisan optimize:clear
print_success "Cache temizlendi"

# 7. Optimize Application
echo ""
print_info "Uygulama optimize ediliyor..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
print_success "Uygulama optimize edildi"

# 8. Storage Link
echo ""
print_info "Storage link kontrol ediliyor..."
php artisan storage:link || true
print_success "Storage link hazır"

# 9. File Permissions
echo ""
print_info "Dosya izinleri ayarlanıyor..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache
print_success "Dosya izinleri ayarlandı"

# 10. Restart Queue Workers
echo ""
print_info "Queue worker'lar yeniden başlatılıyor..."
sudo systemctl restart laravel-worker || true
print_success "Queue worker'lar başlatıldı"

# 11. Restart PHP-FPM
echo ""
print_info "PHP-FPM yeniden başlatılıyor..."
sudo systemctl restart php${PHP_VERSION}-fpm
print_success "PHP-FPM başlatıldı"

# 12. Up Mode
echo ""
print_info "Uygulama yeniden açılıyor..."
php artisan up
print_success "Uygulama çalışıyor"

# Deployment tamamlandı
echo ""
echo "================================================"
print_success "✨ Deployment başarıyla tamamlandı!"
echo "================================================"
echo ""
print_info "Son adımlar:"
echo "  - Uygulamayı test edin"
echo "  - Log dosyalarını kontrol edin: tail -f storage/logs/laravel.log"
echo "  - Monitoring araçlarınızı kontrol edin"
echo ""
