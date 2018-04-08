# Виджеты #
------------

Box
------------
`Lia\Widgets\Box` используется для создания компонентов Box:
```php
use Lia\Widgets\Box;

$box = new Box('Box Title', 'Box content');

$box->removable();

$box->collapsable();

$box->style('info');

$box->solid();

echo $box;
```
Параметр `$content` - это элемент содержимого поля, который может быть либо реализацией интерфейса `Illuminate\Contracts\Support\Renderable`, либо других печатаемых переменных.

Метод `Box::title($title)` используется для установки заголовка компонента Box.

Метод `Box::content($content)` используется для установки элемента содержимого компонента Box.

Метод `Box::removable()` устанавливает компонент Box как закрываемый.

Метод `Box::collapsible()` устанавливает компонент Box как сборный.

Метод `Box::style($style)` задает стиль компонента Box для заполнения `primary`, `info`, `danger`, `warning`, `success`, `default`.

Метод `Box::solid()` добавляет границы к компоненту Box.

Collapse
------------
`Lia\Widgets\Collapse` используемый для создания складываемых компонентов:
```php
use Lia\Widgets\Collapse;

$collapse = new Collapse();

$collapse->add('Bar', 'xxxxx');
$collapse->add('Orders', new Table());

echo $collapse->render();
```
Метод `Collapse::add($title, $content)` используется для добавления свернутого элемента к коллапсирующему компоненту. Параметр `$title` устанавливает заголовок элемента. Параметр `$content` - это элемент содержимого поля, который может быть либо реализацией интерфейса `Illuminate\Contracts\Support\Renderable`, либо других печатаемых переменных.

Form
------------
Класс `Lia\Widgets\Form` используется для быстрой сборки формы:
```php
use Lia\Widgets\Form;

$form = new Form();

$form->action('example');

$form->email('email')->default('qwe@aweq.com');
$form->password('password');
$form->text('name');
$form->url('url');
$form->color('color');
$form->map('lat', 'lng');
$form->date('date');
$form->json('val');
$form->dateRange('created_at', 'updated_at');

echo $form->render();
```
`Form ::__ construct($data = [])` генерирует объект формы. Если параметр `$data` передан, элементы в массиве `$data` будут заполнены в форму.

Метод `Form::action($uri)` используется для установки адреса представления формы.

Метод `Form::method($method)` используется для установки метода отправки формы, по умолчанию используется метод `POST`.

`Form::disablePjax()` отключить pjax для отправки формы.

Infobox
------------
Класс `Lia\Widgets\InfoBox` используется для генерации блока представления информации:
```php
use Lia\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/admin/users', '1024');

echo $infoBox->render();
```

Tab component
------------
Класс `Lia\Widgets\Tab` используется для создания компонентов вкладки:
```php
use Lia\Widgets\Tab;

$tab = new Tab();

$tab->add('Pie', $pie);
$tab->add('Table', new Table());
$tab->add('Text', 'blablablabla....');

echo $tab->render();
```
Метод `Tab::add($title, $content)` используется для добавления вкладки, `$title` для заголовка вкладки, `$content` для содержимого.

Table
------------
Класс `Lia\Widgets\Table` используется для генерации таблиц:
```php
use Lia\Widgets\Table;

// table 1
$headers = ['Id', 'Email', 'Name', 'Company'];
$rows = [
    [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica'],
    [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar'],
    [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC'],
    [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor'],
    [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.'],
];

$table = new Table($headers, $rows);

echo $table->render();

// table 2
$headers = ['Keys', 'Values'];
$rows = [
    'name'   => 'Joe',
    'age'    => 25,
    'gender' => 'Male',
    'birth'  => '1989-12-05',
];

$table = new Table($headers, $rows);

echo $table->render();
```