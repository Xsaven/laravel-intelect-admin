# Управление полем формы #
------------

Удалить поле
------------
Для встроенных полей `map` и` editor` требуются файлы front-end через cdn, и если есть проблемы с сетью, их можно удалить следующими способами

Найдите файл `app/Admin/bootstrap.php`. Если файл не существует, обновите `LIA-admin` и создайте этот файл.
```php
<?php

use Lia\Form;

Form::forget('map');
Form::forget('editor');

// or

Form::forget(['map', 'editor']);
```
Это удаляет два поля, метод можно использовать для удаления других полей.

Добовление пользовательского поля
------------
Добавте редактор кода PHP на основе [codemirror](http://codemirror.net/index.html) со следующими шагами.

см. [PHP mode](http://codemirror.net/mode/php/).

Загрузите и распакуйте [codemirror](http://codemirror.net/codemirror.zip) библиотеку в каталог ресурсов интерфейса, например, в каталог `public/packages/codemirror-5.20.2`.

Создайте новый класс поля `app/Admin/Extensions/PHPEditor.php`:
```php
<?php

namespace App\Admin\Extensions;

use Lia\Form\Field;

class PHPEditor extends Field
{
    protected $view = 'admin.php-editor';

    protected static $css = [
        '/packages/codemirror-5.20.2/lib/codemirror.css',
    ];

    protected static $js = [
        '/packages/codemirror-5.20.2/lib/codemirror.js',
        '/packages/codemirror-5.20.2/addon/edit/matchbrackets.js',
        '/packages/codemirror-5.20.2/mode/htmlmixed/htmlmixed.js',
        '/packages/codemirror-5.20.2/mode/xml/xml.js',
        '/packages/codemirror-5.20.2/mode/javascript/javascript.js',
        '/packages/codemirror-5.20.2/mode/css/css.js',
        '/packages/codemirror-5.20.2/mode/clike/clike.js',
        '/packages/codemirror-5.20.2/mode/php/php.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

CodeMirror.fromTextArea(document.getElementById("{ $this->id }"), {
    lineNumbers: true,
    mode: "text/x-php",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("    " , "end");
        }
     }
});

EOT;
        return parent::render();

    }
}
```
>Статические ресурсы в классе также могут быть импортированы извне, см. [Editor.php](https://github.com/Xsaven/laravel-intelect-admin/blob/master/src/Form/Field/Editor.php)

Создайте файл вида `resources/views/admin/php-editor.blade.php`:
```blade
<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('admin::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
    </div>
</div>
```
Наконец, найдите файл `app/Admin/bootstrap.php`, если файл не существует, обновите `LIA-admin`, а затем создайте этот файл, добавьте следующий код:
```php
<?php

use App\Admin\Extensions\PHPEditor;
use Lia\Form;

Form::extend('php', PHPEditor::class);
```
И тогда вы можете использовать PHP-редактор в [Модель формы](/ru/model_form_basic_usage.md):
```php
$form->php('code');
```
Таким образом, вы можете добавить любые поля формы, которые вы хотите добавить.

Интеграция CKEditor
------------
Вот еще один пример, чтобы показать вам, как интегрировать ckeditor.

Сначала скачать [CKEditor](http://ckeditor.com/download), разархивировать в общий каталог, например `public/packages/ckeditor/`.

Затем напишите класс расширения `app/Admin/Extensions/Form/CKEditor.php`:
```php
<?php

namespace App\Admin\Extensions\Form;

use Lia\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/packages/ckeditor/ckeditor.js',
        '/packages/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'admin.ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{ $this->getElementClassString() }').ckeditor();";

        return parent::render();
    }
}
```
Создайте файл вида `resources/views/admin/ckeditor.blade.php` - `admin.ckeditor` :
```blade
<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>
```
Зарегистрируйте это расширение в `app/Admin/bootstrap.php`:
```php
use Lia\Form;
use App\Admin\Extensions\Form\CKEditor;

Form::extend('ckeditor', CKEditor::class);
```
После этого вы можете использовать ckeditor в своей форме:
```php
$form->ckeditor('content');
```