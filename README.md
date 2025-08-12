# Laravel Task Management

## Kurulum

```bash
git clone https://github.com/beytullahk54/laravel_task_management_api.git 
cd laravel_task_management_api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Test Kullanıcısı

```
Kullanıcı Adı: info@kahraman.com
Şifre: 12345678
```

## Yapılacaklar

- [X] Migrate dosyaları oluşturulacak
- [ ] Validation oluşturulması
- [ ] Storage konfigurasyonu
- [ ] Redis kurulacak
- [ ] Event listener oluşturulacak
- [ ] Middleware oluşturulacak

## Frontend Notları

- Frontend tarafında validasyonlar array olarak dönmektedir. Foreach dönerek hatalar ekranda gösterilebilir