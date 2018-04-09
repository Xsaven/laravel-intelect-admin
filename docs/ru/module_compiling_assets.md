# Компиляция активов (Laravel Mix) #
------------

#### Установка и настройка ####
Когда вы создаете новый модуль, он также создает активы для CSS/JS и `webpack.mix.js` файла конфигурации.
```artisan
php artisan module:make Blog
```

Перейдите в каталог модуля:
```shell
cd Modules/Blog
```

`package.json` Файл по умолчанию включает все что вам нужно для начала работы. Вы можете установить зависимости, на которые он ссылается, и запустить:
```shell
npm install
```

#### Запуск Laravel Mix ####
`Mix` - это слой конфигураций поверх Webpack, поэтому для запуска задач `Mix` вам нужно всего лишь выполнить один из сценариев `NPM`, который включен в `LIA-admin-modules` package.json файл по умолчанию
```shell
// Запуск всех задач Mix...
npm run dev

// Запуск всех задач Mix и минимизация кода...
npm run production
```

После создания файла с версией вы не будете знать точное имя файла. Таким образом, вы должны использовать глобальную функцию `Laravel mix` в своих представлениях для загрузки соответствующего хэшированного актива. Функция `mix` автоматически определяет текущее имя хэшированного файла:

```blade
// Modules/Blog/Resources/views/layouts/master.blade.php

<link rel="stylesheet" href="{{ mix('css/blog.css') }}">

<script src="{{ mix('js/blog.js') }}"></script>
```

Для получения дополнительной информации о Laravel Mix см. [Документацию](https://laravel.com/docs/mix)

>Примечание: чтобы главная конфигурация Laravel Mix не перезаписывала `public/mix-manifest.json` файл:

Установите `laravel-mix-merge-manifest`

```shell
npm install laravel-mix-merge-manifest --save-dev
```
Изменить основной файл `webpack.mix.js`
```javascript
let mix = require('laravel-mix');


/* Allow multiple Laravel Mix applications*/
require('laravel-mix-merge-manifest');
mix.mergeManifest();
/*----------------------------------------*/

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
```
