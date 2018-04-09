# Команды консоли модуля #
------------

Ваш модуль может содержать команды консоли. Вы можете создавать эти команды вручную или со следующим помощником:
```shell
php artisan module:make-command CreatePostCommand Blog
```

Это создаст `CreatePostCommand` внутри модуля Blog. По умолчанию это будет `Modules/Blog/Console/CreatePostCommand`.

Пожалуйста, обратитесь к документации [laravel artisan commands](https://laravel.com/docs/artisan), чтобы узнать все о них.

#### Регистрация команды ####

Вы можете зарегистрировать команду с помощью метода `laravel`, `commands` который доступен внутри класса поставщика услуг.
```php
$this->commands([
    \Modules\Blog\Console\CreatePostCommand::class,
]);
```
Теперь вы можете получить доступ к своей команде через `php artisan` консоль.