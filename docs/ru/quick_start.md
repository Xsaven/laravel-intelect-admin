# Быстрый старт #
------------

Например, мы используем таблицу `users` с` Laravel`, структура таблицы:

```sql
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```

И модель для этой таблицы - `App\User.php`

Вы можете выполнить следующие шаги, чтобы настроить интерфейсы `CURD` таблицы` users`:

#### Добавить контроллер
Используйте следующую команду для создания контроллера для модели `App\User`

```php
php artisan lia:make UserController --model=App\\User

// для windows использовать:
php artisan lia:make UserController --model=App\User
```
Вышеупомянутая команда создаст контроллер `app/Admin/Controllers/UserController.php`.

#### Добавить маршрут
Добавить маршрут в `app/Admin/routes.php`：
```php
$router->resource('demo/users', UserController::class);
```

#### Добавить элемент меню
Откройте `http://localhost/admin/auth/menu`, добавьте ссылку меню и обновите страницу, затем вы можете найти элемент ссылки в левой панели меню.

>В `uri` заполняет часть пути, которая не содержит префикса маршрута, например полный путь `http://localhost/admin/demo/users`, просто введите `demo/users`, если вы хотите добавьте внешнюю ссылку, просто заполните полный URL-адрес, например `https://xsaven.github.io/laravel-intelect-admin`.

#### Создание сетки и формы
Остальное что нужно сделать, это открыть `app/Admin/Contollers/UserController.php`, найти `form()` и `grid()` метод и написать несколько строк кода с `Модель сетки` и `Модель формы`, для более подробной информации, пожалуйста, прочитайте [Модель сетки](/ru/model_grid_basic_usage.md) и [Модель формы](/ru/model_form_basic_usage.md).