<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/docs', function () {
    return view('docs');
});

$router->group(['prefix' => '/api'], function () use ($router) {
    // Auth
    $router->post('/auth/login', ['uses' => 'AuthController@login', 'as' => 'auth.login']);

    // User
    $router->get('/users[/{id}]', ['uses' => 'UserController@show', 'as' => 'user.show']);
    $router->post('/users/upload_photo', ['uses' => 'UserController@uploadAvatar', 'as' => 'user.upload_photo']);
    $router->put('/users', ['uses' => 'UserController@update', 'as' => 'user.update']);
    $router->patch('/users', ['uses' => 'UserController@changePhone', 'as' => 'user.change_phone']);

    // Geography
    $router->get('/countries', ['uses' => 'GeographyController@countries', 'as' => 'geography.countries']);
});

