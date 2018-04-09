# Создание модуля #
------------

Создать модуль очень просто. Выполните следующую команду для создания модуля:
```artisan
php artisan module:make <module-name>
```
Замените `<module-name>` на имя своего модуля.

Также возможно создать несколько модулей в одной команде.
```artisan
php artisan module:make Blog User Auth
```

По умолчанию при создании нового модуля команда добавит некоторые ресурсы, такие как `контроллер`, `класс семян`, `поставщик услуг` и т.д. Автоматически. Если вы этого не хотите, вы можете добавить `--plain` флаг, чтобы создать простой модуль.
```artisan
php artisan module:make Blog --plain
# или
php artisan module:make Blog -p
```

#### Соглашение об именовании ####
Поскольку мы загружаем модули с использованием psr-4 , мы настоятельно рекомендуем использовать соглашение StudlyCase.

#### Структура папок ####
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
          ├── lang/
          ├── views/
      ├── Repositories/
      ├── Tests/
      ├── composer.json
      ├── module.json
      ├── start.php
```