# Фасадные методы #
------------

Получить все модули.
```php
Module::all();
```
Получить все кэшированные модули.
```php
Module::getCached();
```
Получить заказываемые модули. Модули будут упорядочены с помощью `priority` ключа в `module.json` файле.
```php
Module::getOrdered();
```
Получить отсканированные модули.
```php
Module::scan();
```
Найти определенный модуль.
```php
Module::find('name');
// Или
Module::get('name');
```
Найти модуль, если он есть, вернуть `Module` экземпляр, иначе бросить `Lia\Addons\Modules\Exeptions\ModuleNotFoundException`.
```php
Module::findOrFail('module-name');
```
Получить отсканированные пути.
```php
Module::getScanPaths();
```
Получить все модули как экземпляр коллекции.
```php
Module::toCollection();
```
Получить модули по статусу. `1` для активных и `0` для неактивных.
```php
Module::getByStatus(1);
```
Проверить указанный модуль. Если он существует, вернется true, в противном случае false.
```php
Module::has('blog');
```
Получить все включенные модули.
```php
Module::allEnabled();
```
Получить все отключенные модули.
```php
Module::allDisabled();
```
Получить количество всех модулей.
```php
Module::count();
```
Получить путь к модулю.
```php
Module::getPath();
```
Зарегистрировать модули.
```php
Module::register();
```
Загрузить все доступные модули.
```php
Module::boot();
```
Получить все включенные модули в качестве экземпляра коллекции.
```php
Module::collections();
```
Получить путь к модулю от указанного имени.
```php
Module::getModulePath('name');
```
Получить путь к ресурсам из указанного модуля.
```php
Module::assetPath('name');
```
Получить значение конфигурации из этого пакета.
```php
Module::config('composer.vendor');
```
Получить использованный путь хранения.
```php
Module::getUsedStoragePath();
```
Загрузить модуль для сеанса `cli`.
```php
Module::getUsedNow();
// Или
Module::getUsed();
```
Установить используемый модуль для сеанса `cli`.
```php
Module::setUsed('name');
```
Получить путь к активам модулей.
```php
Module::getAssetsPath();
```
Получить URL-адрес актива из определенного модуля. Если опубликована ссылка активов модуля, вернёт путь: `//localhost/blog_assets/css/app.css` если же нет, вернет путь `//localhost/modules/blog/css/app.css`
```php
Module::asset('blog:img/logo.img');
// Или
masset('blog:css/app.css');
```
Установить указанный модуль с помощью данного имени модуля.
```php
Module::install('lia/hello');
```
Обновить зависимости для указанного модуля.
```php
Module::update('hello');
```
Добавить макрос в репозиторий модулей.
```php
Module::macro('hello', function() {
    echo "I'm a macro";
});
```
Вызвать макрос из репозитория модуля.
```php
Module::hello();
```
Получить все необходимые модули, модуля
```php
Module::getRequirements('module name');
```