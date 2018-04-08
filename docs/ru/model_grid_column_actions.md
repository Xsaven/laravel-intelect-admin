# Столбец модели сетки #
------------

В `Модель сетки` встроено много методов для работы со столбцом, вы можете использовать эти методы для гибкой работы с данными столбца.

Объект `Lia\Grid\Column` имеет встроенный метод `display()` для обработки значения текущего столбца через входящую функцию обратного вызова:
```php
$grid->column('title')->display(function ($title) {

    return "<span style='color:blue'>$title</span>";

});
```
Обратный вызов `display`, привязанный к текущему объекту данных строки в качестве родительского объекта, позволяет использовать данные в текущей строке следующим образом:
```php
$grid->first_name();

$grid->last_name();

$grid->column('full_name')->display(function () {
    return $this->first_name . ' ' . $this->last_name;
});
```
>метод `value()` является псевдонимом метода `display()`.

Встроенные методы
------------
`Модель сетки` имеет встроенные методы, которые помогут вам расширить функциональность столбца

#### редактируемые ####
С помощью `editable.js` вы можете напрямую редактировать данные в сетке:
```php
$grid->title()->editable();

$grid->title()->editable('textarea');

$grid->title()->editable('select', [1 => 'option1', 2 => 'option2', 3 => 'option3']);

$grid->birth()->editable('date');

$grid->published_at()->editable('datetime');

$grid->column('year')->editable('year');

$grid->column('month')->editable('month');

$grid->column('day')->editable('day');
```
#### переключатель ####
>notice: Если вы установите переключатель для столбца в сетку, то необходимо установить столбец в виде одного и того же переключателя в форме

Быстро превратите столбец в компонент переключатель, используя следующие методы:
```php
$grid->status()->switch();

// set the `text`,`color`, and `value`
$states = [
    'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
    'off' => ['value' => 2, 'text' => 'NO', 'color' => 'default'],
];
$grid->status()->switch($states);
```
#### группа переключателей ####
>notice: Если вы установите группу переключателей для столбца в сетку, то необходимо установить столбец в виде одного и того же переключателя в форме

Чтобы быстро изменить столбец в группу компонентов коммутатора, используйте следующий метод:
```php
$states = [
    'on' => ['text' => 'YES'],
    'off' => ['text' => 'NO'],
];

$grid->column('switch_group')->switchGroup([
    'hot'       => 'Hot',
    'new'       => 'New'
    'recommend' => 'Recommend',
], $states);
```
#### select ####
```php
$grid->options()->select([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```
#### radio ####
```php
$grid->options()->radio([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```
#### checkbox ####
```php
$grid->options()->checkbox([
    1 => 'Sed ut perspiciatis unde omni',
    2 => 'voluptatem accusantium doloremque',
    3 => 'dicta sunt explicabo',
    4 => 'laudantium, totam rem aperiam',
]);
```
#### изображение ####
```php
$grid->picture()->image();

// Установить хост, ширину и высоту
$grid->picture()->image('http://xxx.com', 100, 100);

// отображать несколько изображений
$grid->pictures()->display(function ($pictures) {

    return json_decode($pictures, true);

})->image('http://xxx.com', 100, 100);
```
#### метка ####
```php
$grid->name()->label();

// Установите цвет, по умолчанию `success`, другие опции `danger`,`warning`,`info`,`primary`,`default`,`success`
$grid->name()->label('danger');

// может обрабатывать массив
$grid->keywords()->label();
```
#### значок ####
```php
$grid->name()->badge();

// Установите цвет, по умолчанию `success`, другие опции `danger`,`warning`,`info`,`primary`,`default`,`success`
$grid->name()->badge('danger');

// может обрабатывать массив
$grid->keywords()->badge();
```

Расширить столбец
------------
Существует два способа расширения функции столбца, первая - через анонимную функцию.

Добавьте следующий код в `app/Admin/bootstrap.php`:
```php
use Lia\Grid\Column;

Column::extend('color', function ($value, $color) {
    return "<span style='color: $color'>$value</span>"
});
```
Используйте это расширение в `Модель сетки`:
```php
$grid->title()->color('#ccc');
```
Если логика отображения столбца более сложна, может реализоваться с классом расширения.

Класс расширения `app/Admin/Extensions/Popover.php`:
```php
<?php

namespace App\Admin\Extensions;

use Lia\Admin;
use Lia\Grid\Displayers\AbstractDisplayer;

class Popover extends AbstractDisplayer
{
    public function display($placement = 'left')
    {
        Admin::script("$('[data-toggle=\"popover\"]').popover()");
        $value = $this->value;

        return <<<EOT
<button type="button"
    class="btn btn-secondary"
    title="popover"
    data-container="body"
    data-toggle="popover"
    data-placement="$placement"
    data-content="$value"
    >
  Popover
</button>

EOT;

    }
}
```
А затем добавьте расширение в `app/Admin/bootstrap.php`：
```php
use Lia\Grid\Column;
use App\Admin\Extensions\Popover;

Column::extend('popover', Popover::class);
```
Используйте расширение в `Модель сетки`：
```php
$grid->desciption()->popover('right');
```

помощники
------------

#### Строковые операции ####
Если текущие выходные данные являются строкой, вы можете вызвать метод класса `Illuminate\Support\Str`.

Например, в следующем столбце показано строковое значение поля `title`:
```php
$grid->title();
```
Вызов `Str::limit()` в столбце `title`.

Может вызывать метод `Str::limit()` в выходной строке столбца `title`.
```php
$grid->title()->limit(30);
```
Продолжить вызов метода `Illuminate\Support\Str`:
```php
$grid->title()->limit(30)->ucfirst();

$grid->title()->limit(30)->ucfirst()->substr(1, 10);
```

#### Операции с массивами ####
Если текущие выходные данные являются массивом, вы можете вызвать метод класса `Illuminate\Support\Collection`.

Например, столбец `tags` представляет собой массив данных, полученных из отношения «один ко многим»:
```php
$grid->tags();

array (
  0 => 
  array (
    'id' => '16',
    'name' => 'php',
    'created_at' => '2016-11-13 14:03:03',
    'updated_at' => '2016-12-25 04:29:35',

  ),
  1 => 
  array (
    'id' => '17',
    'name' => 'python',
    'created_at' => '2016-11-13 14:03:09',
    'updated_at' => '2016-12-25 04:30:27',
  ),
)
```
Вызвать метод `Collection::pluck()` для получения столбца `name` из массива
```php
$grid->tags()->pluck('name');

array (
    0 => 'php',
    1 => 'python',
  ),
```
Выходные данные по-прежнему остаются массивом, поэтому вы можете продолжать вызывать методы `Illuminate\Support\Collection`.
```php
$grid->tags()->pluck('name')->map('ucwords');

array (
    0 => 'Php',
    1 => 'Python',
  ),
```
Выводит массив как строку
```php
$grid->tags()->pluck('name')->map('ucwords')->implode('-');

"Php-Python"
```

#### Смешанное использование ####
В вышеупомянутых двух типах вызовов методов, пока вывод предыдущего шага должен определить тип значения, вы можете вызвать соответствующий тип метода, это может быть очень гибкий микс.

Например, поле `images` представляет собой строковый тип в формате JSON, который хранит массив адресов с несколькими изображениями:
```php
$grid->images();

"['foo.jpg', 'bar.png']"

// вызов цепочки для отображения нескольких изображений
$grid->images()->display(function ($images) {

    return json_decode($images, true);

})->map(function ($path) {

    return 'http://localhost/images/'. $path;

})->image();
```