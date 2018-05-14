# Модель-tree #
------------

Может быть достигнуто с помощью `Модель-tree` до древовидных компонентов, вы можете перетащить путь для достижения уровня данных, сортировки и других операций, следующее основное использование.

Структура и модель таблицы
------------
Чтобы использовать `Модель-tree`, вы должны следовать стандарту структуры таблицы:
```sql
CREATE TABLE `demo_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```
В приведенной выше структуре таблицы есть три обязательных поля `parent_id`, `order`, `title`.

Соответствующая модель `app/Models/Category.php`:
```php
<?php

namespace App\Models\Demo;

use Lia\Traits\AdminBuilder;
use Lia\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelTree, AdminBuilder;

    protected $table = 'demo_categories';
}
```
В структуре таблицы в трех полях `parent_id`, `order`, `title` название поля могут быть изменены:
```php
<?php

namespace App\Models\Demo;

use Lia\Traits\AdminBuilder;
use Lia\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelTree, AdminBuilder;

    protected $table = 'demo_categories';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('pid');
        $this->setOrderColumn('sort');
        $this->setTitleColumn('name');
    }
}
```

Применение
------------

Затем используйте `Модель-tree` на вашей странице
```php
<?php

namespace App\Admin\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Lia\Form;
use Lia\Facades\Admin;
use Lia\Layout\Content;
use Lia\Controllers\ModelForm;
use Lia\Tree;

class CategoryController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Categories');
            $content->body(Category::tree());
        });
    }
}
```
Кнопки управления `Модель-tree`
```php
<?php

namespace App\Admin\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Lia\Form;
use Lia\Facades\Admin;
use Lia\Layout\Content;
use Lia\Controllers\ModelForm;
use Lia\Tree;

class CategoryController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Categories');
            $content->body(Category::tree(function(Tree $tree){
                $tree->disableCreate(); //Отключить кнопку "Создать"
                $tree->disableSave(); //Отключить кнопку "Сохранить"
                $tree->disableRefresh(); //Отключить кнопку "Перезагрузить"
            }));
        });
    }
}
```
Вы можете изменить отображение ветви следующими способами:
```php
Category::tree(function ($tree) {
    $tree->branch(function ($branch) {
        $src = config('lia.upload.host') . '/' . $branch['logo'] ;
        $logo = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";

        return $branch['id']." - ".$branch['title']." ".$logo;
    });
})
```
Параметр `$branch` представляет собой массив данных текущей строки.

Если вы хотите изменить запрос модели, используйте следующий способ:
```php
Category::tree(function ($tree) {

    $tree->query(function ($model) {
        return $model->where('type', 1);
    });

})
```