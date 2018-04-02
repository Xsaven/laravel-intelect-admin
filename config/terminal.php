<?php

return [
    'enabled' => env('APP_DEBUG') === true,
    'whitelists' => [],
    'interpreters' => [
        'mysql' => 'mysql',
        'artisan tinker' => 'tinker',
        'tinker' => 'tinker',
        'js' => 'js'
    ],
    'confirmToProceed' => [
        'artisan' => [
            'migrate',
            'migrate:install',
            'migrate:refresh',
            'migrate:reset',
            'migrate:rollback',
            'db:seed',
        ],
    ],
    'commands' => [
        \Lia\Addons\Terminal\Console\Commands\Artisan::class,
        \Lia\Addons\Terminal\Console\Commands\ArtisanTinker::class,
        \Lia\Addons\Terminal\Console\Commands\Cleanup::class,
        \Lia\Addons\Terminal\Console\Commands\Composer::class,
        \Lia\Addons\Terminal\Console\Commands\Find::class,
        \Lia\Addons\Terminal\Console\Commands\Mysql::class,
        \Lia\Addons\Terminal\Console\Commands\Tail::class,
        \Lia\Addons\Terminal\Console\Commands\Vi::class,
        \Lia\Addons\Terminal\Console\Commands\LS::class,
        \Lia\Addons\Terminal\Console\Commands\CD::class,
    ],
];
