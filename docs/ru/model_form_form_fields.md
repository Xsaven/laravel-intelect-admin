# Встроенные поля формы #
------------
В `Модель формы` встроено множество компонентов формы, которые помогут вам быстро создавать формы.

Общественные методы
------------
#### Установить значение для сохранения ####
```php
$form->text('title')->value('text...');
```
#### Установить значение по умолчанию ####
```php
$form->text('title')->default('text...');
```
#### Установить справочное сообщение ####
```php
$form->text('title')->help('help...');
```
#### Установить атрибуты элемента поля ####
```php
$form->text('title')->attribute(['data-title' => 'title...']);

$form->text('title')->attribute('data-title', 'title...');
```
#### Установить заполнитель ####
```php
$form->text('title')->placeholder('Please input...');
```
#### Модель-форма-вкладка ####
Если форма содержит слишком много полей, это приведет к тому, что страница формы слишком длинная, и в этом случае вы можете использовать вкладку, чтобы отделить форму:
```php
$form->tab('Basic info', function ($form) {

    $form->text('username');
    $form->email('email');

})->tab('Profile', function ($form) {

   $form->image('avatar');
   $form->text('address');
   $form->mobile('phone');

})->tab('Jobs', function ($form) {

     $form->hasMany('jobs', function () {
         $form->text('company');
         $form->date('start_date');
         $form->date('end_date');
     });

})
```

Text input
------------
```php
$form->text($column, $label);

// Добавить правило проверки подачи
$form->text($column, $label)->rules('required|min:10');
```
Select
------------
```php
$form->select($column, $label)->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```
Если у вас слишком много опций, вы можете загрузить параметры с помощью ajax:
```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```
Метод контроллера для api `/admin/api/users`:
```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}
```
Json вернул из api `/admin/demo/options`:
```php
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```
Select linkage
------------
Компонент `select` поддерживает одностороннюю связь отношений родитель-потомок:
```php
$form->select('province')->options(...)->load('city', '/api/city');

$form->select('city');
```
Где `load('city', '/api/city');` означает, что после изменения текущей опции выбора текущая опция вызовет api `/api/city` с помощью аргумента `q` api возвращает данные для заполнения вариантов для окна выбора города, где api `/api/city` возвращает формат данных, который должен соответствовать:
```php
[
    {
        "id": 1,
        "text": "foo"
    },
    {
        "id": 2,
        "text": "bar"
    },
    ...
]
```
Код для действия контроллера выглядит следующим образом:
```php
public function city(Request $request)
{
    $provinceId = $request->get('q');

    return ChinaArea::city()->where('parent_id', $provinceId)->get(['id', DB::raw('name as text')]);
}
```
Multiple select
------------
```php
$form->multipleSelect($column, $label)->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```
Вы можете сохранить значение множественного выбора двумя способами, одним из которых является отношение `многие ко многим`.
```php
class Post extends Models
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

$form->multipleSelect('tags')->options(Tag::all()->pluck('name', 'id'));
```
Второй - сохранить массив параметров в одно поле. Если поле является строковым типом, необходимо определить [accessor and Mutator](https://laravel.com/docs/eloquent-mutators) for the field.

Если у вас слишком много опций, вы можете загрузить опцию с помощью ajax
```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```
Метод контроллера для api `/admin/api/users`:
```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}
```
Json вернул из api `/admin/demo/options`:
```php
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```
Listbox
------------
Использование такое же, как и у mutipleSelect.
```php
$form->listbox($column, $label)->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```
Textarea
------------
```php
$form->textarea($column, $label)->rows(10);
```
Radio
------------
```php
$form->radio($column, $label)->options(['m' => 'Female', 'f'=> 'Male'])->default('m');

$form->radio($column, $label)->options(['m' => 'Female', 'f'=> 'Male'])->default('m')->stacked();
```
Checkbox
------------
`checkbox` может хранить значения двумя способами, см. [multiple select](#multiple-select)

Метод `options()` используется для установки параметров:
```php
$form->checkbox($column, $label)->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);

$form->checkbox($column, $label)->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name'])->stacked();
```
Email input
------------
```php
$form->email($column, $label);
```
Password input
------------
```php
$form->password($column, $label);
```
URL input
------------
```php
$form->url($column, $label);
```
Ip input
------------
```php
$form->ip($column, $label);
```
Phone number input
------------
```php
$form->mobile($column, $label)->options(['mask' => '999 9999 9999']);
```
Color select
------------
```php
$form->color($column, $label)->default('#ccc');
```
Time input
------------
```php
$form->time($column, $label);

// Установите формат времени, больше форматов по ссылке http://momentjs.com/docs/#/displaying/format/    
$form->time($column, $label)->format('HH:mm:ss');
```
Date input
------------
```php
$form->date($column, $label);

// Настройка формата даты, больше форматов, см. http://momentjs.com/docs/#/displaying/format/
$form->date($column, $label)->format('YYYY-MM-DD');
```
Datetime input
------------
```php
$form->datetime($column, $label);

// Установите формат даты, более подробная информация о форматах http://momentjs.com/docs/#/displaying/format/
$form->datetime($column, $label)->format('YYYY-MM-DD HH:mm:ss');
```
Time range select
------------
`$startTime`, `$endTimeis` поля начала и окончания:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```
Date range select
------------
`$startDate`, `$endDateis` поля начала и окончания даты:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```
Datetime range select
------------
`$startDateTime`, `$endDateTime` это начала и окончания datetime поля:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```
Currency input
------------
```php
$form->currency($column, $label);

// установить символ единицы
$form->currency($column, $label)->symbol('￥');
```
Number input
------------
```php
$form->number($column, $label);
```
Rate input
------------
```php
$form->rate($column, $label);
```
Image upload
------------
Прежде чем использовать поле загрузки, вы должны заполнить конфигурацию загрузки, см. [Загрузка файла/изображения](/ru/model_form_file_image_upload.md).

Вы можете использовать сжатие, обрезку, добавление водяных знаков и другие методы, пожалуйста, обратитесь к [Intervention](http://image.intervention.io/getting_started/introduction), каталогу загрузки изображений в файле `config/lia.php` `upload.image`, если каталог не существует, вам необходимо создать каталог и открыть права на запись:
```php
$form->image($column, $label);

// Изменить путь загрузки файла и имя файла
$form->image($column, $label)->move($dir, $name);

// Кадрирование снимка
$form->image($column, $label)->crop(int $width, int $height, [int $x, int $y]);

// Добавить водяной знак
$form->image($column, $label)->insert($watermark, 'center');

// добавить кнопку удаления
$form->image($column, $label)->removable();
```
File upload
------------
Прежде чем использовать поле загрузки, вы должны заполнить конфигурацию загрузки, см. [Загрузка файла/изображения](/en/model_form_file_image_upload.md).

Каталог загрузки файлов настроен в `upload.file` в файле `config/lia.php`. Если каталог не существует, его необходимо создать и установить на запись.
```php
$form->file($column, $label);

// Изменить путь загрузки файла и имя файла
$form->file($column, $label)->move($dir, $name);

// Задайть тип загружаемого файла
$form->file($column, $label)->rules('mimes:doc,docx,xlsx');

// добавить кнопку удаления
$form->file($column, $label)->removable();
```
Multiple image/file upload
------------
```php
// несколько изображений
$form->multipleImage($column, $label);

// несколько файлов
$form->multipleFile($column, $label);

// добавить кнопку удаления
$form->multipleFile($column, $label)->removable();
```
Тип данных, передаваемых из нескольких полей image / file, является массивом, если тип столбца в таблице mysql является массивом или используется mongodb, то вы можете сохранить массив напрямую, но если вы используете строковый тип для хранения данных массива, вам нужно указать строковый формат. Например, если вы хотите использовать строку json для хранения данных массива, вам нужно определить мутатор для столбца в мутаторе модели, например поле с именем `pictures`, определить мутатор:
```php
public function setPicturesAttribute($pictures)
{
    if (is_array($pictures)) {
        $this->attributes['pictures'] = json_encode($pictures);
    }
}

public function getPicturesAttribute($pictures)
{
    return json_decode($pictures, true);
}
```
Конечно, вы также можете указать любой другой формат.

Map
------------
Поле карты относится к сетевому ресурсу, и если есть проблема с сетью, обратитесь к [Управление полем формы](/ru/model_form_form_field_management.md) для удаления компонента.

Используется для выбора широты и долготы, `$latitude`,` $longitude` для поля широты и долготы, используя Google Maps:
```php
$form->map($latitude, $longitude, $label);
```
Slider
------------
Может использоваться для выбора типа цифровых полей, таких как возраст:
```php
$form->slider($column, $label)->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```
Дополнительные параметры см. [https://github.com/IonDen/ion.rangeSlider#settings](https://github.com/IonDen/ion.rangeSlider#settings)

Rich text editor
------------
Поле редактора относится к сетевому ресурсу, и если есть проблема с сетью, обратитесь к [Управление полем формы](/ru/model_form_form_field_management.md) для удаления компонента.
```php
$form->editor($column, $label);
```
Hidden field
------------
```php
$form->hidden($column);
```
Switch
------------
`On` и `off` пары переключателей со значениями `1` и `0`:
```php
$states = [
    'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
    'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
];

$form->switch($column, $label)->states($states);
```
Display field
------------
Только отображение поля и без каких-либо действий:
```php
$form->display($column, $label);
```
Divide
------------
```php
$form->divide();
```
Html
------------
вставить html, переданный аргумент может быть объектами, которые реализуют `Htmlable`,`Renderable` или имеют метод `__toString()`
```php
$form->html('html contents');
```
Tags
------------
Вставьте запятую (,) разделенные строки `tags`
```php
$form->tags('keywords');
```
Icon
------------
Выберать значок `font-awesome`.
```php
$form->icon('icon');
```
HasMany
------------
Встроенные таблицы «один ко многим» для взаимодействия с отношениями «один ко многим». Вот простой пример:

Существует две таблицы: отношения «один ко многим»:
```sql
CREATE TABLE `demo_painters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `demo_paintings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `painter_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY painter_id (`painter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
Модель таблиц:
```php
<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painter extends Model
{
    public function paintings()
    {
        return $this->hasMany(Painting::class, 'painter_id');
    }
}

<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painting extends Model
{
    protected $fillable = ['title', 'body', 'completed_at'];

    public function painter()
    {
        return $this->belongsTo(Painter::class, 'painter_id');
    }
}
```
Создайте код формы следующим образом:
```php
$form->display('id', 'ID');

$form->text('username')->rules('required');
$form->textarea('bio')->rules('required');

$form->hasMany('paintings', function (Form\NestedForm $form) {
    $form->text('title');
    $form->image('body');
    $form->datetime('completed_at');
});

$form->display('created_at', 'Created At');
$form->display('updated_at', 'Updated At');
```

Embeds
------------
Используется для обработки полевых данных типа `JSON` данных `mysql` или `object` типа `mongodb`, или значения данных нескольких полей могут храниться в форме строки `JSON` в типе символов mysql

Например, столбец `extra` `JSON` или строковый тип в таблице заказов, используемый для хранения данных для нескольких полей:
```php
class Order extends Model
{
    protected $casts = [
        'extra' => 'json',
    ];
}
```
И затем используйте в форме:
```php
$form->embeds('extra', function ($form) {

    $form->text('extra1')->rules('required');
    $form->email('extra2')->rules('required');
    $form->mobile('extra3');
    $form->datetime('extra4');

    $form->dateRange('extra5', 'extra6', 'Date range')->rules('required');

});

// Настроить заголовок
$form->embeds('extra', 'Extra', function ($form) {
    ...
});
```
Функция обратного вызова внутри элемента формы для создания вызова метода и внешней является одинаковой.

