# Помощники #
------------

#### Путь к активам `LIA-admin` ####
```php
admin_asset('css/css.css');
```
?>Результат: http://localhost/css/css.css
>Альтернатива `laravel` `asset()` только с настройками `https` конфигурации `LIA-admin` в `config/lia.php` ключ `secure`

```php
admin_vendor_asset('css/css.css');
```
?>Результат: http://localhost/vendor/lia/css/css.css

#### Вывести toastr сообщение для следующего сеанса приложения ####
```php
admin_toastr($message = 'Save success!', $type = 'success', $options = []);
```

#### Сгенерировать ссылку на админпанель ####
```php
$url = admin_url('/auth/users');
```
?>Результат: http://localhost/admin/auth/users
```php
$url = admin_base_path('/auth/users');
```
?>Результат: /admin/auth/users

#### Путь админ сущьности ####
Получить путь к файлам админ панели
```php
admin_path();
```

#### Функция пути модуля ####
Получить путь к данному модулю.
```php
$path = module_path('Blog');
```

#### Путь к активам модуля ####
Получить URL-адрес актива из определенного модуля.
```php
$asset = masset('blog:css/app.css');
```
?>Если опубликована ссылка активов модуля, вернёт путь: `//localhost/blog_assets/css/app.css` если же нет, вернет путь `//localhost/modules/blog/css/app.css`

#### Путь к модулям ####
```php
module_path('blog');
```
?>Результат: E:\domains\localhost\Modules\Blog