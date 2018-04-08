# Пользовательские инструменты #
------------

`Модель сетки` имеет функции `batch delete` и `refresh` по умолчанию, `Модель сетки` предоставляет настраиваемую функциональность инструмента, если есть более оперативные требования, следующий пример покажет вам, как добавить кнопку `Gender selector` групповой инструмент.

Сначала определите класс инструмента `app/Admin/Extensions/Tools/UserGender.php`：
```php
<?php

namespace App\Admin\Extensions\Tools;

use Lia\Admin;
use Lia\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class UserGender extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['gender' => '_gender_']);

        return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_gender_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => 'All',
            'm'     => 'Male',
            'f'     => 'Female',
        ];

        return view('admin.tools.gender', compact('options'));
    }
}
```
Файл вида blade `admin.tools.gender` - `resources/views/admin/tools/gender.blade.php`:
```blade
<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-default btn-sm {{ \Request::get('gender', 'all') == $option ? 'active' : '' }}">
        <input type="radio" class="user-gender" value="{{ $option }}">{{$label}}
    </label>
    @endforeach
</div>
```
Импортируйте этот инструмент в `Модель сетки`：
```php
$grid->tools(function ($tools) {
    $tools->append(new UserGender());
});
```
В `Модель сетки`, передайте запрос `gender` для модели:
```php
if (in_array(Request::get('gender'), ['m', 'f'])) {
    $grid->model()->where('gender', Request::get('gender'));
}
```
Вы можете обратиться к указанному выше способу, чтобы добавить свои собственные инструменты.

Пакетный режим
------------
В настоящее время, по умолчанию реализована операция пакетного удаления, если вы хотите отключить операцию пакетного удаления:
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->disableDelete();
    });
});
```
Если вы хотите добавить пользовательскую пакетную операцию, вы можете обратиться к следующему примеру.

Следующий пример покажет вам, как реализовать операцию `post batch release`:

Сначала определите класс инструмента `app/Admin/Extensions/Tools/ReleasePost.php`：

```php
<?php

namespace App\Admin\Extensions\Tools;

use Lia\Grid\Tools\BatchAction;

class ReleasePost extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT

$('{ $this->getElementClass() }').on('click', function() {

    $.ajax({
        method: 'post',
        url: '{ $this->resource }/release',
        data: {
            _token:LA.token,
            ids: selectedRows(),
            action: { $this->action }
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('Success');
        }
    });
});

EOT;

    }
}
```
См. Приведенный выше код, используйте ajax для передачи выбранных `ids` для back-end api через POST-запрос, back-end api изменяет состояние соответствующих данных в соответствии с полученными `ids`, а затем обновляет интерфейс страницы (pjax reload) и показывает всплывающюю подсказку `toastr` `Success`.

Импортируйте эту операцию в `Модель сетки`：
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->add('Release post', new ReleasePost(1));
        $batch->add('Unrelease post', new ReleasePost(0));
    });
});
```
Чтобы пакетная операция выпадающей кнопки добавила следующие две операции, последний шаг - добавить api для обработки запроса пакетной операции, код api выглядит следующим образом:
```php
class PostController extends Controller
{
    ...

    public function release(Request $request)
    {
        foreach (Post::find($request->get('ids')) as $post) {
            $post->released = $request->get('action');
            $post->save();
        }
    }

    ...
}
```
Затем добавьте маршрут для api выше:
```php
$router->post('posts/release', 'PostController@release');
```
Это завершает весь процесс.