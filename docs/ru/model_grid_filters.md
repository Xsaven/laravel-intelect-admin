# Фильтры сетчатых моделей #
------------

`Модель сетки` Предоставляет набор фильтров данных:
```php
$grid->filter(function($filter){

    // Remove the default id filter
    $filter->disableIdFilter();

    // Add a column filter
    $filter->like('name', 'name');
    ...

});
```

Тип фильтра
------------
В настоящее время поддерживаются следующие типы фильтров:

#### Equal ####
`sql: ... WHERE` column `= ""$input""`：
```php
$filter->equal('column', $label);
```
#### Not equal ####
`sql: ... WHERE` column `!= ""$input""`：
```php
$filter->notEqual('column', $label);
```
#### Like ####
`sql: ... WHERE` column `LIKE "%"$input"%"`：
```php
$filter->like('column', $label);
```
#### Ilike ####
`sql: ... WHERE` column `ILIKE "%"$input"%"`：
```php
$filter->ilike('column', $label);
```
#### Greater then ####
`sql: ... WHERE` column `> "$input"`：
```php
$filter->gt('column', $label);
```
#### Less than ####
`sql: ... WHERE` column `< "$input"`：
```php
$filter->lt('column', $label);
```
#### Between ####
`sql: ... WHERE` column `BETWEEN "$start" AND "$end"`：
```php
$filter->between('column', $label);

// установить тип поля даты и времени
$filter->between('column', $label)->datetime();

// тип поля заданного времени
$filter->between('column', $label)->time();
```
#### In ####
`sql: ... WHERE` column `in (...$inputs)`：
```php
$filter->in('column', $label)->multipleSelect(['key' => 'value']);
```
#### NotIn ####
`sql: ... WHERE` column `not in (...$inputs)`：
```php
$filter->notIn('column', $label)->multipleSelect(['key' => 'value']);
```
#### Date ####
`sql: ... WHERE DATE(` column `) = "$input"`：
```php
$filter->date('column', $label);
```
#### Day ####
`sql: ... WHERE DAY(` column `) = "$input"`：
```php
$filter->day('column', $label);
```
#### Month ####
`sql: ... WHERE MONTH(` column `) = "$input"`：
```php
$filter->month('column', $label);
```
#### year ####
`sql: ... WHERE YEAR(` column `) = "$input"`：
```php
$filter->year('column', $label);
```
#### Where ####
Вы можете использовать `where` для создания более сложной фильтрации запросов

`sql: ... WHERE` title `LIKE "%$input" OR` content `LIKE "%$input"`：
```php
$filter->where(function ($query) {

    $query->where('title', 'like', "%{ $this->input }%")
        ->orWhere('content', 'like', "%{ $this->input }%");

}, 'Text');
```
`sql: ... WHERE` rate `>= 6 AND` created_at `= "$input"`:
```php
$filter->where(function ($query) {

    $query->whereRaw("`rate` >= 6 AND `created_at` = { $this->input }");

}, 'Text');
```
Запрос отношения, запрос соответствующего отношения `profile`:
```php
$filter->where(function ($query) {

    $query->whereHas('profile', function ($query) {
        $query->where('address', 'like', "%{ $this->input }%")->orWhere('email', 'like', "%{ $this->input }%");
    });

}, 'Address or mobile');
```

Тип поля
------------
Тип поля по умолчанию - текстовый ввод, задает заполнитель для ввода текста:
```php
$filter->equal('column')->placeholder('Please input...');
```
Вы также можете ограничить формат ввода пользователя, используя следующие методы:
```php
$filter->equal('column')->url();

$filter->equal('column')->email();

$filter->equal('column')->integer();

$filter->equal('column')->ip();

$filter->equal('column')->mac();

$filter->equal('column')->mobile();

// $options относятся к https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
$filter->equal('column')->decimal($options = []);

// $options относятся к https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
$filter->equal('column')->currency($options = []);

// $options относятся к https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
$filter->equal('column')->percentage($options = []);

// $options относятся к https://github.com/RobinHerbots/Inputmask
$filter->equal('column')->inputmask($options = [], $icon = 'pencil');
```
#### Select ####
```php
$filter->equal('column')->select(['key' => 'value'...]);

// Или из api для получения данных, ссылочная модель-форма формата api, компонент `select`
$filter->equal('column')->select('api/users');
```
#### multipleSelect ####
Обычно используется в сочетании с `in` и `notIn`, необходимо запросить массив двух типов запросов, которые также могут использоваться в типе запроса типа `type`:
```php
$filter->in('column')->multipleSelect(['key' => 'value'...]);

// Или из api, чтобы получить данные, модель-форма формата api `multipleSelect`
$filter->in('column')->multipleSelect('api/users');
```
#### radio ####
Более распространенным сценарием является выбор категорий
```php
$filter->equal('released')->radio([
    ''   => 'All',
    0    => 'Unreleased',
    1    => 'Released',
]);
```
#### checkbox ####
Более распространенная сцена - запрос области с `whereIn`:
```php
$filter->in('gender')->checkbox([
    'm'    => 'Male',
    'f'    => 'Female',
]);
```
#### datetime ####
Используйте компоненты даты и времени, параметр `$options` и ссылку на значение [bootstrap-datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/Options/)
```php
$filter->equal('column')->datetime($options);

// `date()` equals to `datetime(['format' => 'YYYY-MM-DD'])`
$filter->equal('column')->date();

// `time()` equals to `datetime(['format' => 'HH:mm:ss'])`
$filter->equal('column')->time();

// `day()` equals to `datetime(['format' => 'DD'])`
$filter->equal('column')->day();

// `month()` equals to `datetime(['format' => 'MM'])`
$filter->equal('column')->month();

// `year()` equals to `datetime(['format' => 'YYYY'])`
$filter->equal('column')->year();
```