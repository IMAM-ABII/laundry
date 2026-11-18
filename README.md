# Laundrymam

A Laravel laundry ordering app with a customer catalog, order tracking for admins, and CRUD management for laundry services.

## Setup

The project keeps Laravel's SQLite configuration by default. From the project root:

```sh
composer install
npm install
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

The seed creates the initial laundry services and this admin account:

- Email: `admin@gmail.com`
- Password: `password123`

Newly registered accounts receive the `user` role and cannot access admin pages. Do not use the seeded password outside local development; change it before deployment.

Customer ordering is available at `/`. Admin users can manage laundry items and order statuses from `/dashboard`.
