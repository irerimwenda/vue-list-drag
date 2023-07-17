# Installation
The project is setup with [Laravel 10.x](https://laravel.com/docs/10.x/installation) hence PHP ^8.1 is required.

Run the following commands

```
composer install
yarn install
cp .env.example .env
php artisan key:generate
```

Create a database with a name of your choice and fill the following .env property with your database name

```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Finally, run the database migrations and serve your application.

```
php artisan migrate:fresh
```
