# Быстрый пример #
------------

Создайте свой первый модуль c `LIA-admin`:
```artisan
php artisan module:make Blog
```

Будет создана следующая структура:

```$xslt
app/
bootstrap/
vendor/
Modules/
  ├── Blog/
      ├── Assets/
      ├── Config/
      ├── Console/
      ├── Database/
          ├── Migrations/
          ├── Seeders/
      ├── Entities/
      ├── Http/
          ├── Controllers/
          ├── Middleware/
          ├── Requests/
          ├── routes.php
      ├── Providers/
          ├── BlogServiceProvider.php
      ├── Resources/
          ├── assets/
              ├── js/
                ├── app.js
              ├── sass/
                ├── app.scss
          ├── lang/
          ├── views/
      ├── Repositories/
      ├── Tests/
      ├── composer.json
      ├── module.json
      ├── start.php
``` 