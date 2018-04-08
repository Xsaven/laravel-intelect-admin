# Валидация формы #
------------

`Модель формы` использует правила валидации laravel для проверки данных, представленных формой:
```php
$form->text('title')->rules('required|min:3');

// В обратном вызове могут быть реализованы сложные правила валидации
$form->text('title')->rules(function ($form) {

    // If it is not an edit state, add field unique verification
    if (!$id = $form->model()->id) {
        return 'unique:users,email_address';
    }

});
```
Вы также можете настроить сообщение об ошибке для правила проверки:
```php
$form->text('code')->rules('required|regex:/^\d+$/|min:10', [
    'regex' => 'code must be numbers',
    'min'   => 'code can not be less than 10 characters',
]);
```
Если вы хотите, чтобы поле было пустым, сначала убедитесь что в таблице базы данных для поля установлено значение по умолчанию «NULL», а затем
```php
$form->text('title')->rules('nullable');
```
См. Дополнительные правила [Validation](https://laravel.com/docs/5.6/validation).