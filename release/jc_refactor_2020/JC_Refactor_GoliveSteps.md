## Steps to bring up the application

Download project ZIP from GIT repository.

Delete git directory if any and gitignore file.

DO NOT MOVE .env file.

ZIP and copy the code the server

Using Putty execute below commands 

```
composer dump-autoload

php artisan config:cache

php artisan route:clear

php artisan route:list

```

### Installing Laravel Datatable Package for datatable

In Laravel version 5.6 support for  laravel-datatable 8.0

```
composer require yajra/laravel-datatables-oracle:"~8.0"
```
