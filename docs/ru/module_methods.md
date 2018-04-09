# Методы модулей #
------------

Получить объект из определенного модуля.
```php
$module = Module::find('blog');
```
Получить имя модуля.
```php
$module->getName();
```
Получить имя модуля в нижнем регистре.
```php
$module->getLowerName();
```
Получить имя модуля в studlycase.
```php
$module->getStudlyName();
```
Получить путь к модулю.
```php
$module->getPath();
```
Получить дополнительный путь.
```php
$module->getExtraPath('Assets');
```
Отключить указанный модуль.
```php
$module->disable();
```
Включить указанный модуль.
```php
$module->enable();
```
Удалить указанный модуль.
```php
$module->delete();
```
Получите массив требований к модулю. 
>Примечание: это должны быть псевдонимы модуля.

```php
$module->getRequires();
```