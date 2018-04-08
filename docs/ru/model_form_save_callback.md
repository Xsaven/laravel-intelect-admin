# Обратный вызов формы модели #
------------

`Модель формы` в настоящее время имеет два метода для приема функций обратного вызова:
```php
// обратный вызов перед сохранением
$form->saving(function (Form $form) {
    //...
});

// обратный вызов после сохранения
$form->saved(function (Form $form) {
    //...
});
```
Данные формы, которые в настоящее время отправляются, могут быть получены из параметра обратного вызова `$form`:
```php
$form->saving(function (Form $form) {

    dump($form->username);

});
```
Получить данные в модели
```php
$form->saved(function (Form $form) {

    $form->model()->id;

});
```
Может перенаправлять на другие URL-адреса, возвращая экземпляр `Symfony\Component\HttpFoundation\Response` непосредственно в обратном вызове:
```php
$form->saving(function (Form $form) {

    // возвращает простой ответ
    return response('xxxx');

});

$form->saving(function (Form $form) {

    // перенаправить на URL-адрес
    return redirect('/admin/users');

});

$form->saving(function (Form $form) {

    // выбрасывает исключение
    throw new \Exception('Error friends. . .');

});
```
Информация об ошибке или успешной информации на странице:
```php
use Illuminate\Support\MessageBag;

// перенаправить обратно с сообщением об ошибке
$form->saving(function ($form) {

    $error = new MessageBag([
        'title'   => 'title...',
        'message' => 'message....',
    ]);

    return back()->with(compact('error'));
});

// перенаправить обратно с успешным сообщением
$form->saving(function ($form) {

    $success = new MessageBag([
        'title'   => 'title...',
        'message' => 'message....',
    ]);

    return back()->with(compact('success'));
});
```