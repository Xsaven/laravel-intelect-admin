<?php

namespace Lia\Addons\TranslationManager;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('translation-manager', function ($app) {
            $manager = $app->make('Lia\Addons\TranslationManager\Manager');
            return $manager;
        });

        $this->app->singleton('command.translation-manager.reset', function ($app) {
            return new Console\ResetCommand($app['translation-manager']);
        });
        $this->commands('command.translation-manager.reset');

        $this->app->singleton('command.translation-manager.import', function ($app) {
            return new Console\ImportCommand($app['translation-manager']);
        });
        $this->commands('command.translation-manager.import');

        $this->app->singleton('command.translation-manager.find', function ($app) {
            return new Console\FindCommand($app['translation-manager']);
        });
        $this->commands('command.translation-manager.find');

        $this->app->singleton('command.translation-manager.export', function ($app) {
            return new Console\ExportCommand($app['translation-manager']);
        });
        $this->commands('command.translation-manager.export');

        $this->app->singleton('command.translation-manager.clean', function ($app) {
            return new Console\CleanCommand($app['translation-manager']);
        });
        $this->commands('command.translation-manager.clean');
	}

    /**
	 * Bootstrap the application events.
	 *
     * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
        $attributes = [
            'prefix'     => config('lia.route.prefix').'/trans',
            'namespace'  => 'Lia\Addons\TranslationManager',
            'middleware' => config('lia.route.middleware'),
            'as'         => 'trans.'
        ];

        $router->group($attributes, function($router)
        {
            $router->get('/{groupKey?}', 'Controller@getIndex')->where('groupKey', '.*')->name('getIndex');
            $router->post('/add/{groupKey}', 'Controller@postAdd')->where('groupKey', '.*')->name('postAdd');
            $router->post('/edit/{groupKey}', 'Controller@postEdit')->where('groupKey', '.*')->name('postEdit');
            $router->post('/groups/add', 'Controller@postAddGroup')->name('postAddGroup');
            $router->post('/delete/{groupKey}/{translationKey}', 'Controller@postDelete')->where('groupKey', '.*')->name('postDelete');
            $router->post('/import', 'Controller@postImport')->name('postImport');
            $router->post('/find', 'Controller@postFind')->name('postFind');
            $router->post('/locales/add', 'Controller@postAddLocale')->name('postAddLocale');
            $router->post('/locales/remove', 'Controller@postRemoveLocale')->name('postRemoveLocale');
            $router->post('/publish/{groupKey}', 'Controller@postPublish')->where('groupKey', '.*')->name('postPublish');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('translation-manager',
            'command.translation-manager.reset',
            'command.translation-manager.import',
            'command.translation-manager.find',
            'command.translation-manager.export',
            'command.translation-manager.clean'
        );
	}

}
