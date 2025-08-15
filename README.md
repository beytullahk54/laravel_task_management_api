# Laravel Task Management

## Kurulum

```bash
git clone https://github.com/beytullahk54/laravel_task_management_api.git 
cp .env.example .env
composer install

.env dosyasını düzenledikten sonra;
php artisan app:install çalıştırmanız yeterlidir. Gerisini sistem yapacaktır.

İşlemler tamamlandktan sonra;
php artisan serve

Farklı bir sekmede;
php artisan queue:work 
```

## Test Kullanıcısı

```
Kullanıcı Adı: Oluşturduğunuz mail adreslerinden birisi
Şifre: 12345678
```

# Açıklamalar
- Maillerin size ulaşabilmesi için smtp bilgilerinizi düzenlemeyi unutmayınız.
- Postman içine postman-collection.json dosyasını aktararak API endpointlerine ulaşabilirsiniz.

## Frontend Notları

- Frontend tarafında validasyonlar array olarak dönmektedir. Foreach dönerek hatalar ekranda gösterilebilir
- Görsellerde Task files relationship ile dönmektedir. Görseller için path'in başına 'storage/' eklenmelidir. 