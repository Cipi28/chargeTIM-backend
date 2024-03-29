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


$router->group(['prefix' => 'api/v1'], function () use ($router) {
    /** @see \App\Http\Controllers\AuthController */
    $router->post('login', 'AuthController@login');
//    $router->get('logout', 'AuthController@logout');
//    $router->get('refresh', 'AuthController@refresh');
//    $router->get('me', 'AuthController@me');

    $router->group(['middleware' => ['auth']], function () use ($router) {

        /** @see \App\Http\Controllers\UsersController */
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UsersController@index');
            $router->post('create', 'UsersController@create');
            $router->get('/{id}', 'UsersController@show');
            $router->put('/{id}', 'UsersController@update');
            $router->delete('/{id}', 'UsersController@destroy');
        });
    });

});
