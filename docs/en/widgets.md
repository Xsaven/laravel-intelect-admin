# Web widgets #
------------

Box
------------
`Lia\Widgets\Box` used to generate box components:
```php
use Lia\Widgets\Box;

$box = new Box('Box Title', 'Box content');

$box->removable();

$box->collapsable();

$box->style('info');

$box->solid();

$box->addTool('<a class="btn btn-sm"><i class="fa fa-home"></i></a>');

echo $box;
```
The `$content` parameter is the content element of the Box, which can be either an implementation of the `Illuminate\Contracts\Support\Renderable` interface, or other printable variables.

The `Box::title($title)` method is used to set the Box component title.

The `Box::content($content)` method is used to set the content element of a Box component.

The `Box::removable()` method sets the Box component as removable.

The `Box::collapsable()` method sets the Box component as collapsable.

`Box::style($style)` method sets the style of the Box component to fill in `primary`,`info`,`danger`,`warning`,`success`,`default`.

The `Box::solid()` method adds a border to the Box component.

The `Box::addTool()` adds html to the toolbox of the Box component.

Collapse
------------
`Lia\Widgets\Collapse` class used to generate folding components:
```php
use Lia\Widgets\Collapse;

$collapse = new Collapse();

$collapse->add('Bar', 'xxxxx');
$collapse->add('Orders', new Table());

echo $collapse->render();
```
The `Collapse::add($title, $content)` method is used to add a collapsed item to the collapsing component. The `$title` parameter sets the title of the item. The `$content` parameter is used to.

Form
------------
`Lia\Widgets\Form` class is used to quickly build a form：
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
`Form::__construct($data = [])` generates a form object. If the `$data` parameter is passed, the elements in the `$data` array will be filled into the form.

`Form::action($uri)` method is used to set the form submission address.

`Form::method($method)` method is used to set the submit method of the form form, the default is `POST` method.

`Form::disablePjax()` disable pjax for form submit.

Infobox
------------
The `Lia\Widgets\InfoBox` class is used to generate the information presentation block:
```php
use Lia\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/admin/users', '1024');

echo $infoBox->render();
```

Tab component
------------
The `Lia\Widgets\Tab` class is used to generate the tab components:
```php
use Lia\Widgets\Tab;

$tab = new Tab();

$tab->add('Pie', $pie);
$tab->add('Table', new Table());
$tab->add('Text', 'blablablabla....');

echo $tab->render();
```
The `Tab::add($title, $content)` method is used to add a tab, `$title` for the option title, and the `$content` tab for the content.

Table
------------
`Lia\Widgets\Table` class is used to generate tables：
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