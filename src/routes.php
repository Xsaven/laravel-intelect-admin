<?php

$attributes = [
    'prefix'     => config('lia.route.prefix'),
    'namespace'  => 'Lia\Controllers',
    'middleware' => config('lia.route.middleware'),
];

Route::group($attributes, function ($router) {

    $router->group(['prefix' => 'module', 'as' => 'mod.', 'namespace' => 'ModulesControllers'], function () {
        Route::get('list', 'ListController@index')->name('list');
        Route::post('list', 'ListController@create')->name('list.create');
        Route::post('cmd', 'ListController@cmd')->name('list.cmd');
        Route::match(['post','get'], 'ide', 'IdeController@cmd')->name('ide.cmd');
    });

    $router->group(['prefix' => 'theme', 'as' => 'them.'], function () {
        Route::get('list', 'ThemeController@index')->name('list');
        Route::match(['post','get'], 'remote', 'ThemeController@cmd')->name('cmd');
    });

    $router->group(['prefix' => 'remote', 'as' => 'remote.'], function () {
        Route::match(['post'], '/post/{name}/{method}', 'RemoteDataControler@post')->name('post');
        Route::match(['get'], '/get/{name}', 'RemoteDataControler@get')->name('get');
        Route::match(['get'], '/select/{name}', 'RemoteDataControler@select')->name('select');
        Route::match(['post'], '/insert/{name}', 'RemoteDataControler@insert')->name('insert');
        Route::match(['post'], '/update/{name}', 'RemoteDataControler@update')->name('update');
        Route::match(['delete'], '/delete/{name}', 'RemoteDataControler@delete')->name('delete');
    });

    $router->group(['prefix' => 'terminal', 'as' => 'terminal.'], function(){
        Route::get('/', 'TerminalController@index')->name('index');
        Route::get('/media/{file}', 'TerminalController@media')->where(['file' => '.+'])->name('media');
        Route::post('/endpoint', 'TerminalController@endpoint')->name('endpoint');
    });

});