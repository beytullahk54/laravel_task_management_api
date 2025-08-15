# Laravel Task Management

## Kurulum

```bash
git clone https://github.com/beytullahk54/laravel_task_management_api.git 
cp .env.example .env
composer install

.env dosyasını düzenledikten sonra;
php artisan app:install çalıştırmanız yeterlidir. Gerisini sistem yapacaktır.
php artisan serve
```

## Test Kullanıcısı

```
Kullanıcı Adı: info@kahraman.com
Şifre: 12345678
```

# Açıklamalar
- Mailleri test edebilmek için lütfen user mail adreslerini kendi adreslerini olarak güncelleyin
- Maillerin size ulaşabilmesi için smtp bilgilerinizi düzenlemeyi unutmayınız.

## Frontend Notları

- Frontend tarafında validasyonlar array olarak dönmektedir. Foreach dönerek hatalar ekranda gösterilebilir
- Görsellerde Task files relationship ile dönmektedir. Görseller için path'in başına 'storage/' eklenmelidir. 