# Регистрация событий модуля #
------------

Ваш модуль может содержать события и прослушиватели событий. Вы можете создавать эти классы вручную или со следующими помощниками:
```shell
php artisan module:make-event BlogPostWasUpdated Blog
php artisan module:make-listener NotifyAdminOfNewPost Blog
```

Как только они создаются, вам необходимо зарегистрировать их в `laravel`. Это можно сделать двумя способами:
  * Вручную вызвать `$this->app['events']->listen(BlogPostWasUpdated::class, NotifyAdminOfNewPost::class);` метод вашего поставщика услуг модуля
  * Или создав поставщика событий для своего модуля, который будет содержать все его события, похожие на `EventServiceProvider` в пространстве имен `app/`.
  
#### Создание EventServiceProvider ####
После того, как у вас будет несколько событий, вам может быть проще иметь все события и их слушателей в выделенном поставщике услуг. Для этого необходим `EventServiceProvider`.

Создайте новый класс, вызываемый для экземпляра `EventServiceProvider` в `Modules/Blog/Providers` папке (например, в качестве имени примера).

Этот класс должен выглядеть так:
```php
<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];
}
```

>Не забудьте загрузить этот поставщик услуг, например, добавив его в файл module.json вашего модуля.

Теперь это похоже на обычный `EventServiceProvider` в `app/` пространстве имен. В нашем примере `listen` свойство будет выглядеть так:
```php
// ...
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BlogPostWasUpdated::class => [
            NotifyAdminOfNewPost::class,
        ],
    ];
}
```