# Модель сетки #
------------

Класс `Lia\Grid` используется для генерации таблиц на основе модели данных, например, у нас есть таблица фильмы в базе данных:
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
И модель этой таблицы - `App\Models\Movie`. Следующий код может генерировать сетку данных для таблицы `movies`:
```php
use App\Models\Movie;
use Lia\Grid;
use Lia\Facades\Admin;

$grid = Admin::grid(Movie::class, function(Grid $grid){

    // Первый столбец отображает поле id и устанавливает столбец как сортируемый
    $grid->id('ID')->sortable();

    // Во втором столбце отображается поле заголовка, так как имя поля заголовка и метод заголовка объекта Grid конфликтуют, используем метод column() Grid
    $grid->column('title');

    // Третий столбец показывает поле director, которое задается методом display($callback) для отображения соответствующего имени пользователя с таблицы пользователей
    $grid->director()->display(function($userId) {
        return User::find($userId)->name;
    });

    // Четвертый столбец отображается как поле describe
    $grid->describe();

    // Пятый столбец отображается как поле rate
    $grid->rate();

    Шестой столбец показывает released поле, форматируя вывод через метод отображения display($callback)
    $grid->released('Release?')->display(function ($released) {
        return $released ? 'yes' : 'no';
    });

    // Ниже показаны столбцы для трех временных полей
    $grid->release_at();
    $grid->created_at();
    $grid->updated_at();

    // Метод filter($callback) используется для настройки простого окна поиска для таблицы
    $grid->filter(function ($filter) {

        // Устанавливает запрос диапазона для поля created_at
        $filter->between('created_at', 'Created Time')->datetime();
    });
});
```

Основное использование
------------

#### Добавить столбец ####
```php
// Добавить столбец непосредственно для имени поля `username`
$grid->username('Username');

// Эффект такой же, как и выше
$grid->column('username', 'Username');

// Добавить несколько столбцов
$grid->columns('email', 'username' ...);
```
#### Изменение исходных данных ####
```php
$grid->model()->where('id', '>', 100);

$grid->model()->orderBy('id', 'desc');

$grid->model()->take(100);
```
#### Устанавливает количество строк, отображаемых на странице ####
```php
// Значение по умолчанию - 15 на страницу
$grid->paginate(20);
```
#### Изменить вывод столбца ####
```php
$grid->text()->display(function($text) {
    return str_limit($text, 30, '...');
});

$grid->name()->display(function ($name) {
    return "<span class='label'>$name</span>";
});

$grid->email()->display(function ($email) {
    return "mailto:$email";
});

// столбца нет в таблице
$grid->column('column_not_in_table')->display(function () {
    return 'blablabla....';
});
```
Закрытие, переданное методу `display()`, является привязкой к объекту данных строки, вы можете использовать другие данные столбца в текущей строке.
```php
$grid->first_name();
$grid->last_name();

// столбца нет в таблице
$grid->column('full_name')->display(function () {
    return $this->first_name.' '.$this->last_name;
});
```
#### Отключите кнопку добовления ####
```php
$grid->disableCreateButton();
```
#### Отключить разбиение на страницы ####
```php
$grid->disablePagination();
```
#### Отключить фильтр данных ####
```php
$grid->disableFilter();
```
#### Отключить кнопку экспорта ####
```php
$grid->disableExport();
```
#### Отключить селектор строк ####
```php
$grid->disableRowSelector();
```
#### Отключить действия в строке ####
```php
$grid->disableActions();
```
#### Включить настраиваемую сетку ####
```php
$grid->orderable();
```
#### Параметры для селектора perPage ####
```php
$grid->perPages([10, 20, 30, 40, 50]);
```

Связь
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
Вы можете связать их в сетке со следующим кодом:
```php
Admin::grid(User::class, function (Grid $grid) {

    $grid->id('ID')->sortable();

    $grid->name();
    $grid->email();

    $grid->column('profile.age');
    $grid->column('profile.gender');

    //или
    $grid->profile()->age();
    $grid->profile()->gender();

    $grid->created_at();
    $grid->updated_at();
});
```

#### Один ко многим ####
Таблицы `posts` и `comments` генерируют ассоциацию «один ко многим» через поле `comments.post_id`
```sql
CREATE TABLE `posts` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `comments` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`post_id` int(10) unsigned NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
Соответствующей моделью данных являются:
```php
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
```
Вы можете связать их в сетке со следующим кодом:
```php
return Admin::grid(Post::class, function (Grid $grid) {
    $grid->id('id')->sortable();
    $grid->title();
    $grid->content();

    $grid->comments('Comments count')->display(function ($comments) {
        $count = count($comments);
        return '<span class="label label-warning">'.$count.'</span>';
    });

    $grid->created_at();
    $grid->updated_at();
});


return Admin::grid(Comment::class, function (Grid $grid) {
    $grid->id('id');
    $grid->post()->title();
    $grid->content();

    $grid->created_at()->sortable();
    $grid->updated_at();
});
```
#### Многие ко многим ####
Таблицы `users` и `role` производят отношения «многие ко многим» через сводную таблицу `role_user`
```sql
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```
Соответствующей моделью данных являются:
```php
class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
```
Вы можете связать их в сетке со следующим кодом:
```php
return Admin::grid(User::class, function (Grid $grid) {
    $grid->id('ID')->sortable();
    $grid->username();
    $grid->name();

    $grid->roles()->display(function ($roles) {

        $roles = array_map(function ($role) {
            return '<span class="label label-success">'.$role['name'].'</span>';
        }, $roles);

        return join('&nbsp;', $roles);
    });

    $grid->created_at();
    $grid->updated_at();
});
```