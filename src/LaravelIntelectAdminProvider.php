<?php

namespace Lia;

use Illuminate\Support\ServiceProvider;
use Lia\Addons\Reporter\Reporter;
use Lia\Addons\Terminal\Kernel;
use Lia\Addons\Terminal\Application as TerminalApplication;

class LaravelIntelectAdminProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Lia\Console\MakeCommand',
        'Lia\Console\InstallCommand',
        'Lia\Console\UninstallCommand',
        'Lia\Console\ImportCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth'       => \Lia\Middleware\Authenticate::class,
        'admin.pjax'       => \Lia\Middleware\Pjax::class,
        'admin.log'        => \Lia\Middleware\LogOperation::class,
        'admin.permission' => \Lia\Middleware\Permission::class,
        'admin.bootstrap'  => \Lia\Middleware\Bootstrap::class,
        'jwt.auth'         => \Lia\Addons\JWTAuth\Middleware\GetUserFromToken::class,
        'jwt.refresh'      => \Lia\Addons\JWTAuth\Middleware\RefreshToken::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
            'admin.permission',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config/lia.php', 'lia' );
        $this->loadTranslationsFrom( __DIR__ . '/../lang', 'lia' );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lia');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if (file_exists($routes = admin_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'lia-config');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'lia-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'lia-migrations');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/lia')], 'lia-assets');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);

        $this->mergeConfigFrom(__DIR__.'/../config/terminal.php', 'terminal');

        $this->app->singleton(TerminalApplication::class, function ($app) {
            $config = $app['config']['terminal'];
            $artisan = new TerminalApplication($app, $app['events'], $app->version());
            $artisan->resolveCommands($config['commands']);

            return $artisan;
        });

        $this->app->singleton(Kernel::class, function ($app) {
            $config = $app['config']['terminal'];

            return new Kernel($app[TerminalApplication::class], array_merge($config, [
                'basePath' => base_path(),
                //'basePath' => cookie('cd_base_path'),
                'environment' => $app->environment(),
                'version' => $app->version(),
                'endpoint' => $app['url']->route('terminal.endpoint'),
            ]));
        });
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('lia.auth', []), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
