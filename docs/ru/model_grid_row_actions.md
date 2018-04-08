# Действия строки сетки модели # 
------------
В `Модель сетки` по умолчанию существуют два действия: `edit` и `delete`, которые можно отключить следующим образом:
```php
$grid->actions(function ($actions) {
    $actions->disableDelete();
    $actions->disableEdit();
});
```
Вы можете получить данные для текущей строки по параметру `$actions`, переданному в:
```php
$grid->actions(function ($actions) {

    // массив данных для текущей строки
    $actions->row;

    // получает значение первичного ключа текущей строки
    $actions->getKey();
});
```
Если вам нужна пользовательская кнопка действий, вы можете добавить следующее:
```php
$grid->actions(function ($actions) {

    // добавить действие после.
    $actions->append('<a href=""><i class="fa fa-eye"></i></a>');

    // добавить действие перед.
    $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');
}
```
Если у вас более сложные действия, вы можете ссылаться на следующие способы:

Сначала определите класс действия:
```php
<?php

namespace App\Admin\Extensions;

use Lia\Admin;

class CheckRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {

    // Ваш код.
    console.log($(this).data('id'));

});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='".$this->id."'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}
```
Затем добавьте действие:
```php
$grid->actions(function ($actions) {

    // добавить действие
    $actions->append(new CheckRow($actions->getKey()));
}
```