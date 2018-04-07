<?php

return [

    /*
     * Laravel Intelect Admin html title.
     */
    'title' => 'Admin',

    /*
     * Laravel Intelect Admin name.
     */
    'name' => 'Laravel Intelect Admin',

    /*
     * Logo in admin panel header.
     */
    'logo' => '<b>LIntelect</b> Admin',

    /*
     * Mini-logo in admin panel header.
     */
    'logo-mini' => '<b>LIA</b>',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'admin',

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web', 'admin'],
    ],

    /*
     * Laravel Intelect Admin install directory.
     */
    'directory' => app_path('Admin'),

    /*
     * Laravel Intelect Admin modules install directory.
     */
    'modules' =>  base_path('Modules'),

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * Laravel Intelect Admin auth setting.
     */
    'auth' => [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => Lia\Auth\Database\Administrator::class,
            ],
        ],
    ],

    /*
     * Laravel Intelect Admin upload setting.
     */
    'upload' => [

        'disk' => 'admin',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * Laravel Intelect Admin database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => Lia\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => Lia\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => Lia\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Lia\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
        'reporter_table'         => 'laravel_exceptions',
        'translate_manager'      => 'translations',
    ],

    /*
     * By setting this option to open or close operation log in Laravel Intelect Admin.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs
         */
        'except' => [
            'admin/auth/logs*',
        ],
    ],

    /*
     * @see https://adminlte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['fixed', 'layout-boxed'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.0',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],

    'translate_manager' => [
        /**
         * Enable deletion of translations
         *
         * @type boolean
         */
        'delete_enabled' => true,
        /**
         * Exclude specific groups from Laravel Translation Manager.
         * This is useful if, for example, you want to avoid editing the official Laravel language files.
         *
         * @type array
         *
         * 	array(
         *		'pagination',
         *		'reminders',
         *		'validation',
         *	)
         */
        'exclude_groups' => [],

        /**
         * Export translations with keys output alphabetically.
         */
        'sort_keys ' => false,
    ]

];