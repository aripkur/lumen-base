# BASE PROJECT LUMEN

Base project lumen spesifikasi

 - Lumen v 10 [laravel-lumen-v10)](https://lumen.laravel.com/docs/10.x)
 - JWT ([tymon/jwt-auth)](https://jwt-auth.readthedocs.io/en/develop/lumen-installation/)
- AdminLte html template [(adminLte)](https://adminlte.io/)
- Captcha 

Ada masalah di project ini selalu nulis error di log walaupun gak ada request. errornya seperti ini :

    [2024-01-18  00:11:38] local.ERROR: Uncaught Illuminate\Contracts\Container\BindingResolutionException: Unresolvable dependency resolving [Parameter #1 [ <required> $cachePath ]] in class Illuminate\View\Compilers\Compiler in C:\laragon\www\lumen-base\vendor\illuminate\container\Container.php:1141
Ketika ada request gak ada masalah, hanya saat idle.

## Init

Migration

    php artisan migrate --seed
Run

    php -S localhost:8080 -t public
  
  atau laragon vhost (apache)
  

    listen 8080
    <VirtualHost *:8080> 
	    DocumentRoot "C:/laragon/www/lumen-base/public"
	    ServerName lumen-base.test
	    ServerAlias *.lumen-base.test
	    <Directory "C:/laragon/www/lumen-base/public">
	        AllowOverride All
	        Require all granted
	    </Directory> 
    </VirtualHost>

Buat catatan saja, dari pada dihapus :)
