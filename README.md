<p align="center"><h1>Laravel Intelect Admin.</h1></p>

Requirements
------------
 - PHP >= 7.1.0
 - Laravel >= 5.5.0
 - Fileinfo PHP Extension

Screenshots
------------

![LIA-Soft](https://raw.githubusercontent.com/Xsaven/laravel-intelect-admin/master/docs/screenshot-soft-filemanager.jpg)
------------
![LIA-Soft](https://raw.githubusercontent.com/Xsaven/laravel-intelect-admin/master/docs/screenshot-soft-module.jpg)
------------
![LIA-Admin](https://raw.githubusercontent.com/Xsaven/laravel-intelect-admin/master/docs/screenshot-admin.jpg)

Installation
------------
> This package requires PHP 7.1+ and Laravel 5.5

First, install laravel 5.5, and make sure that the database connection settings are correct.

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

Open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

Configurations
------------
The file `config/lia.php` contains an array of configurations, you can find the default configurations in there.

Exception reporter
------------
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

File system
------------ 
Add the disk in you `config/filesystems.php` file:
```
'admin' => [
    'driver' => 'local',
    'root' => public_path('uploads'),
    'visibility' => 'public',
    'url' => env('APP_URL').'/uploads',
],
```

Detect missing translations
------------

Most translations can be found by using the Find command (see above), but in case you have dynamic keys (variables/automatic forms etc), it can be helpful to 'listen' to the missing translations.
To detect missing translations, we can swap the Laravel TranslationServiceProvider with a custom provider.
In your config/app.php, comment out the original TranslationServiceProvider and add the one from this package:

    //'Illuminate\Translation\TranslationServiceProvider',
    'Lia\Addons\TranslationManager\TranslationServiceProvider',

This will extend the Translator and will create a new database entry, whenever a key is not found, so you have to visit the pages that use them.
This way it shows up in the webinterface and can be edited and later exported.
You shouldn't use this in production, just in development to translate your views, then just switch back.

Autoloading
-------------

By default the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

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

**Tip: don't forget to run `composer dump-autoload` afterwards.**

Other
------------
`Laravel Intelect Admin` based on following plugins or services:

+ [Laravel](https://laravel.com/)
+ [Laravel Web Artisan](https://github.com/recca0120/laravel-terminal)
+ [Laravel 5 Translation Manager](https://github.com/barryvdh/laravel-translation-manager)
+ [Google Translate PHP](https://github.com/Stichoza/google-translate-php)

License
------------
`Laravel Intelect Admin` is licensed under [The MIT License (MIT)](LICENSE).
