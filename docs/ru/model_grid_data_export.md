# Экспорт данных #
------------

Встроенная функция экспорта `Модель сетки` заключается в достижении простого экспорта файлов формата CSV, если вы столкнулись с проблемой кодирования файлов или не можете удовлетворить свои собственные потребности, вы можете выполнить следующие шаги, чтобы настроить функцию экспорта

Этот пример использует [Laravel-Excel](https://github.com/Maatwebsite/Laravel-Excel) в качестве библиотеки excel, конечно же вы можете использовать любую другую библиотеку excel.

Сначала установите его:
```php
composer require maatwebsite/excel:~2.1.0

php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```
Затем создайте новый пользовательский класс экспорта, например `app/Admin/Extensions/ExcelExpoter.php`:
```php
<?php

namespace App\Admin\Extensions;

use Lia\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // This logic get the columns that need to be exported from the table data
                $rows = collect($this->getData())->map(function ($item) {
                    return array_only($item, ['id', 'title', 'content', 'rate', 'keywords']);
                });

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}
```
А затем используйте этот класс в `Модель сетки`:
```php
use App\Admin\Extensions\ExcelExpoter;

$grid->exporter(new ExcelExpoter());
```
Для получения дополнительной информации о том, как использовать `Laravel-Excel`, обратитесь к [laravel-excel/docs](https://laravel-excel.maatwebsite.nl/docs/3.0/getting-started/basics)