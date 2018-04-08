# Installation #
------------
> This package requires PHP 7.1+ and Laravel 5.6

!> **Step 1**

First, install laravel 5.6, and make sure that the database connection settings are correct.

```
composer require xsaven/laravel-intelect-admin
```

Then run these commands to publish assets and config：
```
php artisan vendor:publish --provider="Lia\LaravelIntelectAdminProvider"
```
After run command you can find config file in `config/lia.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install. 
```
php artisan lia:install
```

------------

!> **Step 2**

Open `app/Exceptions/Handler.php`, call `Reporter::report()` inside `report` method:
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

!> **Step 3**

In your `config/app.php`, comment out the original TranslationServiceProvider and add the one from this package:
```php
//'Illuminate\Translation\TranslationServiceProvider',
'Lia\Addons\TranslationManager\TranslationServiceProvider',
```

------------

!> **Step 4**

You can autoload your modules using `psr-4`. Add the following lines to file `composer.json`:

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

!> **Tip: don't forget to run `composer dump-autoload` afterwards.**

>Open http://localhost/admin/ in browser,use username admin and password admin to login.