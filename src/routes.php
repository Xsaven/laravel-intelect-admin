<?php

$attributes = [
    'prefix'     => config('lia.route.prefix'),
    'namespace'  => 'Lia\Controllers',
    'middleware' => ['api', 'jwt.auth'],
];

Route::group($attributes, function ($router) {

    $router->post('get/configs', 'AuthController@configs');

    $router->group(['prefix' => 'module', 'as' => 'mod.', 'namespace' => 'ModulesControllers'], function () {
        Route::get('list', 'ListController@index')->name('list');
        Route::post('list', 'ListController@create')->name('list.create');
        Route::post('cmd', 'ListController@cmd')->name('list.cmd');
        Route::match(['post','get'], 'ide', 'IdeController@cmd')->name('ide.cmd');
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

    Route::get('soft', 'SoftController@index')->name('soft');
});