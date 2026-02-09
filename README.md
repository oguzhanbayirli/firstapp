# FirstApp - Laravel Projesi

Modern bir Laravel 11 uygulaması. Livewire ile gerçek zamanlı özellikler, post yönetimi, takip sistemi ve sohbet özelliği içerir.

## Gereksinimler

- **PHP** >= 8.3
- **Composer**
- **Node.js** >= 18
- **npm** veya **yarn**
- **SQLite** (veya başka bir veritabanı)

## Kurulum

### 1. Projeyi Klonla

```bash
git clone <repository-url>
cd firstapp
```

### 2. Bağımlılıkları Yükle

```bash
# PHP bağımlılıkları
composer install

# Node.js bağımlılıkları
npm install
```

### 3. Ortam Dosyasını Oluştur

```bash
cp .env.example .env
```

### 4. Uygulama Anahtarını Oluştur

```bash
php artisan key:generate
```

### 5. Veritabanını Yönet

```bash
# Migrasyonları çalıştır
php artisan migrate

# (İsteğe Bağlı) Örnek verilerle seed
php artisan db:seed
```

### 6. Frontend Dosyalarını Derle

```bash
# Development ortamında
npm run dev

# Production ortamında
npm run build
```

## Geliştirme Sunucusunu Başlat

### Seçenek 1: Laravel Artisan

```bash
php artisan serve
```

Uygulama http://localhost:8000 adresinde açılacaktır.

### Seçenek 2: Ayrı Terminallerde

Terminal 1 - Laravel Sunucusu:
```bash
php artisan serve
```

Terminal 2 - Vite Dev Server:
```bash
npm run dev
```

## Proje Yapısı

```
app/
├── Console/       # Artisan komutları
├── Events/        # Event sınıfları (ChatMessage, FirstExampleEvent)
├── Http/
│   ├── Controllers/  # HTTP kontrolörleri
│   └── Middleware/   # HTTP middleware
├── Livewire/      # Livewire bileşenleri
│   ├── Chat.php
│   ├── CreatePost.php
│   ├── AddFollow.php
│   └── ...
├── Mail/          # Mail sınıfları
├── Models/        # Eloquent modelleri (User, Post, Follow)
├── Policies/      # Yetkilendirme politikaları
├── Services/      # Servis katmanı
└── Providers/     # Service providers

resources/
├── css/           # CSS dosyaları
├── js/            # JavaScript dosyaları
└── views/         # Blade şablonları

routes/
├── web.php        # Web rotaları
└── channels.php   # Broadcasting kanalları

database/
├── migrations/    # Veritabanı migrasyonları
├── factories/     # Factory'ler
└── seeders/       # Seederler
```

## Özellikler

- ✅ User Authentication
- ✅ Post Oluşturma, Düzenleme ve Silme
- ✅ Gerçek Zamanlı Sohbet (Livewire)
- ✅ Takip/Unfollow Sistemi
- ✅ Avatar Yükleme
- ✅ Arama Özelliği
- ✅ Email Bildirimleri
- ✅ Cache Stratejileri

## Kullanılan Teknolojiler

- **Laravel 11** - PHP framework
- **Livewire** - Gerçek zamanlı PHP bileşenleri
- **Vite** - Frontend araç zinciri
- **SQLite** - Veritabanı (varsayılan)
- **Tailwind CSS** - Stil

## Yapılandırma

### .env Dosyası

`.env.example` dosyası, gerekli tüm yapılandırma değişkenlerini içerir. Kendi ortamınıza göre ayarlayın:

```env
APP_NAME=FirstApp
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

## Veritabanı

Proje varsayılan olarak SQLite kullanır. İhtiyacınız varsa `.env` dosyasında değiştirebilirsiniz:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=firstapp
DB_USERNAME=root
DB_PASSWORD=
```

## Sık Sorulanlar

### Q: "Composer install" hatası alıyorum
**A:** PHP 8.3 veya daha yüksek sürümünün kurulu olduğundan emin olun.

### Q: "npm install" hatası alıyorum
**A:** Node.js 18+ ve npm'in kurulu olduğundan emin olun:
```bash
node --version
npm --version
```

### Q: Frontend değişiklikleri görmüyorum
**A:** Vite dev server'ın çalışıyor olduğundan emin olun:
```bash
npm run dev
```

### Q: `SQLSTATE[HY000]: General error: 1 no such table`
**A:** Migrasyonları çalıştırtan:
```bash
php artisan migrate
```

## Destek

Sorularınız veya sorunlarınız varsa, lütfen bir issue açın.

## Lisans

Bu proje MIT Lisansı altında lisanslanmıştır.
