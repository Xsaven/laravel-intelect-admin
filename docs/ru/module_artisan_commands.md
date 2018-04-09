# Команды Artisan #
------------

!> Полезный совет: Вы можете использовать следующие команды с `--help` суффиксом, чтобы узнать его аргументы и параметры.

>Обратите внимание, что все следующие команды используют `Blog` в качестве примера имени модуля и пример пространства имен класса/файла

# Команды утилиты #

#### module:make ####
Создать новый модуль.
```shell
php artisan module:make Blog
```

#### module:make ####
Сгенерировать сразу несколько модулей.
```shell
php artisan module:make Blog User Auth
```

#### module:use ####
Использовать данный модуль. Это позволяет не указывать имя модуля в других командах, требующих имени модуля в качестве аргумента.
```shell
php artisan module:use Blog
```

#### module:unuse ####
Это отключает указанный модуль, который был установлен с помощью `module:use` команды.
```shell
php artisan module:unuse
```

#### module:list ####
Показать полный список доступных модулей
```shell
php artisan module:list
```

#### module:migrate ####
Запустить миграции модуля, без аргумента, запустит миграйию всех модулей.
```shell
php artisan module:migrate Blog
```

#### module:migrate-rollback ####
Откат миграций модуля, без аргумента откат всех модулей.
```shell
php artisan module:migrate-rollback Blog
```

#### module:migrate-refresh ####
Обновить миграцию для данного модуля или без указанного модуля обновите все миграции модулей.
```shell
php artisan module:migrate-refresh Blog
```

#### module:migrate-reset ####
Сбросить миграцию для данного модуля или без указанного модуля сбросит все миграции модулей.
```shell
php artisan module:migrate-reset Blog
```

#### module:seed ####
Посев данных для модуля или без аргумента, запустит посев всех модулей.
```shell
php artisan module:seed Blog
```

#### module:publish-migration ####
Публикация файлов миграции для данного модуля или без аргумента публикует все миграции модулей.
```shell
php artisan module:publish-migration Blog
```

#### module:publish-config ####
Публикация файлов конфигурации модуля или без аргумента публикует все файлы конфигурации модулей.
```shell
php artisan module:publish-config Blog
```

#### module:publish-translation ####
Публикация файлов перевода для данного модуля или без указанного модуля публикует все переводы модулей.
```shell
php artisan module:publish-translation Blog
```

#### module:enable ####
Включить данный модуль.
```shell
php artisan module:enable Blog
```

#### module:disable ####
Отключить данный модуль.
```shell
php artisan module:disable Blog
```

#### module:update ####
Обновить данный модуль.
```shell
php artisan module:update Blog
```

# Команды генератора #

#### module:make-command ####
Создайть данную консольную команду для указанного модуля.
```shell
php artisan module:make-command CreatePostCommand Blog
```

#### module:make-migration ####
Создайть миграцию для указанного модуля.
```shell
php artisan module:make-migration create_posts_table Blog
```

#### module:make-seed ####
Создайть заданное имя семени для указанного модуля.
```shell
php artisan module:make-seed seed_fake_blog_posts Blog
```

#### module:make-controller ####
Создайть контроллер для указанного модуля.
```shell
php artisan module:make-controller PostsController Blog
```

#### module:make-model ####
Создайть данную модель для указанного модуля.
```shell
php artisan module:make-model Post Blog
```
Дополнительные опции:
  * `--fillable=field1,field2`: установить заполняемые поля в сгенерированной модели
  * `--migration`, `-m`: создать файл миграции для данной модели
  
#### module:make-provider ####
Создайть данное имя поставщика услуг для указанного модуля.
```shell
php artisan module:make-provider BlogServiceProvider Blog
```

#### module:make-middleware ####
Создайть данный middleware для указанного модуля.
```shell
php artisan module:make-middleware CanReadPostsMiddleware Blog
```

#### module:make-mail ####
Создать данный класс почты для указанного модуля.
```shell
php artisan module:make-mail SendWeeklyPostsEmail Blog
```

#### module:make-notification ####
Создайть данное имя класса уведомлений для указанного модуля.
```shell
php artisan module:make-notification NotifyAdminOfNewComment Blog
```

#### module:make-listener ####
Создайть данный слушатель для указанного модуля. При желании вы можете указать, какой класс событий он должен прослушать. Он также принимает `--queued` флаг, разрешенный для прослушивания событий в очереди.
```shell
php artisan module:make-listener NotifyUsersOfANewPost Blog
php artisan module:make-listener NotifyUsersOfANewPost Blog --event=PostWasCreated
php artisan module:make-listener NotifyUsersOfANewPost Blog --event=PostWasCreated --queued
```

#### module:make-request ####
Создайть данный request для указанного модуля.
```shell
php artisan module:make-request CreatePostRequest Blog
```

#### module:make-event ####
Создайть данное событие для указанного модуля.
```shell
php artisan module:make-event BlogPostWasUpdated Blog
```

#### module:make-job ####
Создайть заданное задание для указанного модуля.
```shell
php artisan module:make-job JobName Blog
php artisan module:make-job JobName Blog --sync # Синхронный класс работы
```

#### module:route-provider ####
Создание данного поставщика услуг маршрута для указанного модуля.
```shell
php artisan module:route-provider Blog
```

#### module:make-factory ####
Создать данную фабрику базы данных для указанного модуля.
```shell
php artisan module:make-factory FactoryName Blog
```

#### module:make-policy ####
Создайть данный класс политики для указанного модуля.

`Policies` Не генерируется по умолчанию при создании нового модуля. Измените значение `paths.generator.policies` в `modules.php`.
```shell
php artisan module:make-policy PolicyName Blog
```

#### module:make-rule ####
Создайть данный класс правил проверки для указанного модуля.

При `Rules` создании нового модуля папка не создается по умолчанию. Измените значение `paths.generator.rules` в `modules.php`.
```shell
php artisan module:make-rule ValidationRule Blog
```

#### module:make-resource ####
Создайть данный класс ресурсов для указанного модуля. Он может иметь необязательный `--collection` аргумент для генерации коллекции ресурсов.

При `Transformers` создании нового модуля папка не создается по умолчанию. Измените значение `paths.generator.resource` в `modules.php`.
```shell
php artisan module:make-resource PostResource Blog
php artisan module:make-resource PostResource Blog --collection
```

#### module:make-test ####
Создайть данный test класс для указанного модуля.
```shell
php artisan module:make-test EloquentPostRepositoryTest Blog
```