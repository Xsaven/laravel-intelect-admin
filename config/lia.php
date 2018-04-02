<?php

return [

    /*
     * LaravelIDE-admin html title.
     */
    'title' => 'Admin',

    /*
     * LaravelIDE-admin name.
     */
    'name' => 'LaravelIDE-Admin',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'admin',

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web', 'admin'],
    ],

    /*
     * LaravelIDE-admin install directory.
     */
    'directory' => app_path('Admin'),

    /*
     * LaravelIDE-admin modules install directory.
     */
    'modules' =>  base_path('Modules'),

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * LaravelIDE-admin auth setting.
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
     * LaravelIDE-admin upload setting.
     */
    'upload' => [

        'disk' => 'admin',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * LaravelIDE-admin database setting.
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
        'role_menu_table'        => 'admin_role_menu',
        'reporter_table'         => 'laravel_exceptions',
        'translate_manager'      => 'translations',
    ],

    /*
     * By setting this option to open or close operation log in LaravelIDE-admin.
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