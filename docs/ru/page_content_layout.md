Контент страницы
------------

Использование макета `LIA-admin` можно найти в методе `index()` файла макета домашней страницы [HomeController.php](https://github.com/Xsaven/laravel-intelect-admin/blob/master/src/Console/stubs/HomeController.stub).

Класс `Lia\Layout\Content` используется для реализации макета области содержимого. Для добавления содержимого страницы используется метод `Content::body($element)`:

Код страницы для незаполненного контента выглядит следующим образом:

```php
public function index()
{
    return Admin::content(function (Content $content) {

        // optional
        $content->header('page header');

        // optional
        $content->description('page description');

        // add breadcrumb
        $content->breadcrumb(
            ['text' => 'Dashboard', 'url' => '/admin'],
            ['text' => 'User management', 'url' => '/admin/users'],
            ['text' => 'Edit user']
        );

        // Fill the page body part, you can put any renderable objects here
        $content->body('hello world');
    });
}
```

Метод `$content->body();` может принимать любые визуализируемые объекты, такие как строка, число, класс, который имеет метод `__toString`, или реализует интерфейс `Renderable`, `Htmlable`, включает объекты Laravel View.

Макет
------------

`LIA-admin` использует сетчатую систему [Bootstrap](https://getbootstrap.com/), длина каждой строки составляет 12, несколько простых примеров:

Добавить строку содержания:
```php
$content->row('hello')

---------------------------------
|hello                          |
|                               |
|                               |
|                               |
|                               |
|                               |
---------------------------------
```
Добавить несколько столбцов в строку:
```php
$content->row(function(Row $row) {
    $row->column(4, 'foo');
    $row->column(4, 'bar');
    $row->column(4, 'baz');
});
----------------------------------
|foo       |bar       |baz       |
|          |          |          |
|          |          |          |
|          |          |          |
|          |          |          |
|          |          |          |
----------------------------------


$content->row(function(Row $row) {
    $row->column(4, 'foo');
    $row->column(8, 'bar');
});
----------------------------------
|foo       |bar                  |
|          |                     |
|          |                     |
|          |                     |
|          |                     |
|          |                     |
----------------------------------
```
Столбец в столбце:
```php
$content->row(function (Row $row) {

    $row->column(4, 'xxx');

    $row->column(8, function (Column $column) {
        $column->row('111');
        $column->row('222');
        $column->row('333');
    });
});
----------------------------------
|xxx       |111                  |
|          |---------------------|
|          |222                  |
|          |---------------------|
|          |333                  |
|          |                     |
----------------------------------
```
Добавить строки в строки и добавить столбцы:
```php
$content->row(function (Row $row) {

    $row->column(4, 'xxx');

    $row->column(8, function (Column $column) {
        $column->row('111');
        $column->row('222');
        $column->row(function(Row $row) {
            $row->column(6, '444');
            $row->column(6, '555');
        });
    });
});
----------------------------------
|xxx       |111                  |
|          |---------------------|
|          |222                  |
|          |---------------------|
|          |444      |555        |
|          |         |           |
----------------------------------
```