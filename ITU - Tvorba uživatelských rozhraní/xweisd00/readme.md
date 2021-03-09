# itu

1. stiahnuť XAMPP https://www.apachefriends.org/index.html a spustit apache a mySQL server
2. stiahnuť composer https://getcomposer.org/
3. V phpMyAdmin vytvoriť databázu itu a nastaviť heslo (cez browser localhost/phpmyadmin)
4. .env.example premenovať na .env
5. v env zmeniť :
  DB_DATABASE=laravel
  DB_USERNAME=root
  DB_PASSWORD=
  na :
  DB_DATABASE=itu
  DB_USERNAME=root
  DB_PASSWORD=vaše heslo phpMyAdmin
6. v cmd v adresári, kde máte projekt (najlepšie xampp/htdocs/itu) spustite:

  1. composer install (alebo php composer.phar install)
  2. npm install
  3. npm run dev
  4. php artisan key:generate
  5. php artisan migrate
  6. php artisan db:seed
  7. php artisan serve
