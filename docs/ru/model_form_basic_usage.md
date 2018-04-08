# Модель Фоормы #
------------

Класс `Lia\Form` используется для создания формы на основе модели данных. Например, в базе данных есть таблица `movies`
```sql
CREATE TABLE `movies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(10) unsigned NOT NULL,
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` tinyint unsigned NOT NULL,
  `released` enum(0, 1),
  `release_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
Соответствующей моделью данных является `App\Models\Movie`, и следующий код может генерировать форму данных `movies`:
```php
use App\Models\Movie;
use Lia\Form;
use Lia\Facades\Admin;

$grid = Admin::form(Movie::class, function(Form $grid){

    // Отображает идентификатор записи
    $form->display('id', 'ID');

    // Добавить поле ввода текста
    $form->text('title', 'Movie title');

    $directors = [
        'John'  => 1,
        'Smith' => 2,
        'Kate'  => 3,
    ];

    $form->select('director', 'Director')->options($directors);

    // Добавить текстовое поле для поля describe
    $form->textarea('describe', 'Describe');

    // Числовой ввод
    $form->number('rate', 'Rate');

    // Добавить поле переключателя
    $form->switch('released', 'Released?');

    // Добавить поле выбора даты и времени
    $form->dateTime('release_at', 'release time');

    // Отобразить два столбца времени 
    $form->display('created_at', 'Created time');
    $form->display('updated_at', 'Updated time');
});
```

Пользовательские инструменты
------------
В правом верхнем углу формы есть два инструмента кнопки по умолчанию. Вы можете изменить его следующим образом:
```php
$form->tools(function (Form\Tools $tools) {

    // Отключить кнопку «Назад».
    $tools->disableBackButton();

    // Отключить кнопку списка.
    $tools->disableListButton();

    // Добавьте кнопку, аргумент может быть строкой или экземпляром объекта, реализующего интерфейс Renderable или Htmlable
    $tools->add('<a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;delete</a>');
});
```

Другие методы
------------
Отключить кнопку отправки:
```php
$form->disableSubmit();
```
Отключить кнопку сброса:
```php
$form->disableReset();
```
Игнорировать поля для хранения
```php
$form->ignore('column1', 'column2', 'column3');
```
Установить ширину для метки и поля
```php
$form->setWidth(10, 2);
```
Настроить action формы
```php
$form->setAction('admin/users');
```

Модельные отношения
------------

#### Один к одному ####
Таблица `users` и таблица `profiles` генерируются взаимно однозначным отношением через поле `profiles.user_id`.
```sql
CREATE TABLE `users` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `profiles` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
Соответствующей моделью данных являются:
```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```
Вы можете связать их в форме со следующим кодом:
```php
Admin::form(User::class, function (Form $form) {

    $form->display('id');

    $form->text('name');
    $form->text('email');

    $form->text('profile.age');
    $form->text('profile.gender');

    $form->datetime('created_at');
    $form->datetime('updated_at');
});
```