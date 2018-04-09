# Пользовательская проверка #
------------
Если вы не используете логику входа в систему логики `LIA-admin`, вы можете ссылаться на следующий способ настройки логики аутентификации входа

Прежде всего, вам нужно определить `User provider`, используемый для получения идентификатора пользователя, например `app/Providers/CustomUserProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class CustomUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {}

    public function retrieveByToken($identifier, $token)
    {}

    public function updateRememberToken(Authenticatable $user, $token)
    {}

    public function retrieveByCredentials(array $credentials)
    {
        // Используйте $credentials для получения пользовательских данных, а затем возвратите объект, реализующий интерфейс `Illuminate\Contracts\Auth\Authenticatable` 
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Проверьте пользователя с паролем имени пользователя в $credentials, верните `true` или` false`
    }
}
```
В методах `retrieveByCredentials` и `validateCredentials` параметр `$credentials` является массивом имени пользователя и пароля, представленным на странице входа, вы можете использовать `$credentials` для реализации своей логики входа в систему

Определение интерфейса `Illuminate\Contracts\Auth\Authenticatable`:
```php
<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable {

    public function getAuthIdentifierName();
    public function getAuthIdentifier();
    public function getAuthPassword();
    public function getRememberToken();
    public function setRememberToken($value);
    public function getRememberTokenName();

}
```
Подробнее о пользовательской аутентификации см. В разделе [adding-custom-user-providers](https://laravel.com/docs/authentication#adding-custom-user-providers)

После создания пользовательского провайдера вам необходимо будет расширить Laravel:
```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('custom', function ($app, array $config) {

            // Верните экземпляр Illuminate\Contracts\Auth\UserProvider...
            return new CustomUserProvider();
        });
    }
}
```
Наконец, измените конфигурацию, откройте `config/lia.php`, найдите `auth` часть:
```php
'auth' => [
    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ]
    ],

    // Измените следующие
    'providers' => [
        'admin' => [
            'driver' => 'custom',
        ]
    ],
],
```
Это завершает логику пользовательской аутентификации