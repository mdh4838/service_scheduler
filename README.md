```
#Local setup

cd service_scheduler
composer self-update --2
composer install
cp .env.example .env
php artisan key:generate
php artisan test

```





