# Загрузка файла/изображения #
------------

[Модель формы](/ru/model_form_basic_usage.md) может создавать поле для загрузки файлов и изображений со следующими кодами
```php
$form->file('file_column');
$form->image('image_column');
```
#### Изменение пути и имени магазина ####
```php
// изменить путь загрузки
$form->image('picture')->move('public/upload/image1/');

// использовать уникальное имя (md5(uniqid()).extension)
$form->image('picture')->uniqueName();

// указать имя файла
$form->image('picture')->name(function ($file) {
    return 'test.'.$file->guessExtension();
});
```
[Модель формы](/ru/model_grid_basic_usage.md) как поддержка локального, так и облачного хранения

#### Загрузить на локальный ####
сначала добавьте конфигурацию хранилища, добавьте диск в `config/filesystems.php`:
```php
'disks' => [
    ... ,

    'admin' => [
        'driver' => 'local',
        'root' => public_path('uploads'),
        'visibility' => 'public',
        'url' => env('APP_URL').'/uploads',
    ],
],
```
установить путь загрузки `public/upload` (public_path('upload')).

И затем в `config/lia.php` выберите `диск`, установленный выше:
```php
'upload'  => [

    'disk' => 'admin',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],
],
```
Установите `disk` в` admin`, который вы добавили выше, `directory.image` и `directory.file` - путь загрузки для `$form->image($column)` и `$form->file($column)`.

`host` - это префикс для ваших загруженных файлов.

#### Загрузить в облако ####
Если вам нужно загрузить в облачное хранилище, необходимо установить драйвер, который поддерживает адаптер `flysystem`, например, взять облачное хранилище `qiniu`.

сначала установить [zgldh/qiniu-laravel-storage](https://github.com/zgldh/qiniu-laravel-storage).

Также настройте диск, в `config/filesystems.php` добавьте элемент:
```php
'disks' => [
    ... ,
    'qiniu' => [
        'driver'  => 'qiniu',
        'domains' => [
            'default'   => 'xxxxx.com1.z0.glb.clouddn.com', 
            'https'     => 'dn-yourdomain.qbox.me',       
            'custom'    => 'static.abc.com',              
         ],
        'access_key'=> '',  //AccessKey
        'secret_key'=> '',  //SecretKey
        'bucket'    => '',  //Bucket
        'notify_url'=> '',  //
        'url'       => 'http://of8kfibjo.bkt.clouddn.com/',
    ],
],
```
Затем измените конфигурацию загрузки `LIA-admin` и откройте `config/lia.php`, чтобы найти:
```php
'upload'  => [

    'disk' => 'qiniu',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],
],
```
Выберите вышеуказанную конфигурацию `qiniu` для `disk`