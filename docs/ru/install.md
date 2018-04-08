# Установка #
------------
> Для этого пакета требуется PHP 7.1+ и Laravel 5.6

!> **Шаг 1**

Сначала установите laravel 5.6 и убедитесь, что настройки подключения к базе данных верны.

```
composer require xsaven/laravel-intelect-admin
```

Затем запустите эти команды для публикации активов и конфигурации:
```
php artisan vendor:publish --provider="Lia\LaravelIntelectAdminProvider"
```
После выполнения команды вы можете найти файл конфигурации в `config/lia.php`, в этом файле вы можете изменить каталог установки, соединение db или имена таблиц.

Наконец, запустите следующую команду, чтобы завершить установку. 
```
php artisan lia:install
```

------------

!> **Шаг 2**

Откройте файл `app/Exceptions/Handler.php`, добавте `Reporter::report()` в `report` метод:
```php
<?php

namespace App\Exceptions;

use Lia\Addons\Reporter\Reporter;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    ...

    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            Reporter::report($exception);
        }

        //parent::report($exception);
    }
    
    ...

}
```

------------

!> **Шаг 3**

В вашем `config/app.php`, закомментируйте оригинальный` TranslationServiceProvider` и добавьте его из этого пакета:
```php
//'Illuminate\Translation\TranslationServiceProvider',
'Lia\Addons\TranslationManager\TranslationServiceProvider',
```

------------

!> **Шаг 4**

Для загрузки модулей с помощью `psr-4`. Добавьте следующие строки в файл `composer.json`:

``` json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/"
    }
  }
}
```

!> **Совет: Не забудьте запустить `comper dump-autoload`**

>Откройте `http://localhost/admin/` в браузере, используйте имя пользователя `admin` и пароль` admin` для входа.