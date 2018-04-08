# Настроить панель навигации заголовка #
------------

Вы можете добавить элемент html в верхнюю панель навигации, откройте `app/Admin/bootstrap.php`：
```php
use Lia\Facades\Admin;

Admin::navbar(function (\Lia\Widgets\Navbar $navbar) {

    $navbar->left('html...');

    $navbar->right('html...');

});
```
метод `left` и `right` используется для добавления содержимого левой и правой сторон заголовка, параметры метода могут быть любыми объектами, которые могут быть визуализированы (объекты, которые реализуют `Htmlable`, `Renderable` или имеют метод `__toString()`) или строки.

Добавить элементы влево
------------
Например, добавить строку поиска слева, сначала создайте представление вида `resources/views/search-bar.blade.php`：
```blade
<style>

.search-form {
    width: 250px;
    margin: 10px 0 0 20px;
    border-radius: 3px;
    float: left;
}
.search-form input[type="text"] {
    color: #666;
    border: 0;
}

.search-form .btn {
    color: #999;
    background-color: #fff;
    border: 0;
}

</style>

<form action="/admin/posts" method="get" class="search-form" pjax-container>
    <div class="input-group input-group-sm ">
        <input type="text" name="title" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span>
    </div>
</form>
```
Затем добавьте его в панель навигации заголовка:
```php
$navbar->left(view('search-bar'));
```

Добавить элементы вправо
------------
Вы можете добавить тег `<li>` в правой части навигации, например добавить несколько значков подсказок, создать новый класс рендеринга `app/Admin/Extensions/Nav/Links.php`
```php
<?php

namespace App\Admin\Extensions\Nav;

class Links
{
    public function __toString()
    {
        return <<<HTML

<li>
    <a href="#">
      <i class="fa fa-envelope-o"></i>
      <span class="label label-success">4</span>
    </a>
</li>

<li>
    <a href="#">
      <i class="fa fa-bell-o"></i>
      <span class="label label-warning">7</span>
    </a>
</li>

<li>
    <a href="#">
      <i class="fa fa-flag-o"></i>
      <span class="label label-danger">9</span>
    </a>
</li>

HTML;
    }
}
```
Затем добавьте его в панель навигации заголовка:
```php
$navbar->right(new \App\Admin\Extensions\Nav\Links());
```
Или используйте следующий html, чтобы добавить раскрывающееся меню:
```blade
<li class="dropdown notifications-menu">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-bell-o"></i>
  <span class="label label-warning">10</span>
</a>
<ul class="dropdown-menu">
  <li class="header">You have 10 notifications</li>
  <li>
    <!-- inner menu: contains the actual data -->
    <ul class="menu">
      <li>
        <a href="#">
          <i class="fa fa-users text-aqua"></i> 5 new members joined today
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
          page and may cause design problems
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-users text-red"></i> 5 new members joined
        </a>
      </li>

      <li>
        <a href="#">
          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-user text-red"></i> You changed your username
        </a>
      </li>
    </ul>
  </li>
  <li class="footer"><a href="#">View all</a></li>
</ul>
</li>
```
Дополнительные компоненты можно найти здесь [Bootstrap](https://getbootstrap.com/)