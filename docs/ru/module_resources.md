# Ресурсы модулей #
------------

Ваш модуль, скорее всего, будет содержать то, что `laravel` называет ресурсами, которые содержат конфигурацию, представления, файлы перевода и т.д. Для того, чтобы модуль правильно загружался или если хотите опубликовать его, вам нужно сообщить о них, как в любом обычном пакете.

!> Эти ресурсы загружаются в сервис-провайдер, сгенерированный модулем (используя module:make), если только флаг `plain` не использовался, и в этом случае вам придется самому обрабатывать эту логику.

!> Не забудьте изменить пути в следующих фрагментах кода, предполагается что модуль «Blog».

#### конфигурация ####
```php
$this->publishes([
    __DIR__.'/../Config/config.php' => config_path('blog.php'),
], 'config');
$this->mergeConfigFrom(
    __DIR__.'/../Config/config.php', 'blog'
);
```
Представления вида
```php
$viewPath = base_path('resources/views/modules/blog');

$sourcePath = __DIR__.'/../Resources/views';

$this->publishes([
    $sourcePath => $viewPath
]);

$this->loadViewsFrom(array_merge(array_map(function ($path) {
    return $path . '/modules/blog';
}, \Config::get('view.paths')), [$sourcePath]), 'blog');
```
Основная часть здесь - `loadViewsFrom` вызов метода. Если вы не хотите, чтобы ваши представления были опубликованы в папке просмотра `laravel`, вы можете удалить вызов для метода `$this->publishes()`.

#### Языковые файлы ####
```php
$langPath = base_path('resources/lang/modules/blog');

if (is_dir($langPath)) {
    $this->loadTranslationsFrom($langPath, 'blog');
} else {
    $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'blog');
}
```
#### Фабрики ####
Если вы хотите использовать фабрики `laravel`, вам придется добавить в свой сервис:
```php
$this->app->singleton(Factory::class, function () {
    return Factory::construct(__DIR__ . '/Database/factories');
});
```