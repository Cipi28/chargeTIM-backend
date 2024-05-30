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
    $router->post('register', 'AuthController@register');
//    $router->get('logout', 'AuthController@logout');
//    $router->get('refresh', 'AuthController@refresh');
//    $router->get('me', 'AuthController@me');

    $router->group(['middleware' => ['auth']], function () use ($router) {

        /** @see \App\Http\Controllers\UsersController */
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UsersController@index');
            $router->post('create', 'UsersController@create');
            $router->get('/{id}', 'UsersController@show');
            $router->patch('/{id}', 'UsersController@update');
            $router->delete('/{id}', 'UsersController@destroy');
        });

        /** @see \App\Http\Controllers\CarsController */
        $router->group(['prefix' => 'cars'], function () use ($router) {
            $router->get('/{userId}', 'CarsController@index');
            $router->post('create/{userId}', 'CarsController@create');
            $router->patch('/update/{id}', 'CarsController@update');
            $router->delete('/delete/{id}', 'CarsController@delete');
        });

        /** @see \App\Http\Controllers\StationsController */
        $router->group(['prefix' => 'stations'], function () use ($router) {
            $router->post('save/', 'StationsController@create');
            $router->post('/', 'StationsController@addStation');
            $router->delete('/{id}', 'StationsController@delete');
            $router->get('{id}/', 'StationsController@index');
            $router->get('/', 'StationsController@getStations');
            $router->get('/user/{userId}', 'StationsController@getUserStations');
        });

            /** @see \App\Http\Controllers\FavouriteStationsController */
        $router->group(['prefix' => 'favourite-stations'], function () use ($router) {
            $router->get('/{userId}', 'FavouriteStationsController@index');
            $router->get('/index/{userId}', 'FavouriteStationsController@getFavouriteStationsIndex');
            $router->post('/{userId}/{stationId}', 'FavouriteStationsController@create');
            $router->delete('/{userId}/{stationId}', 'FavouriteStationsController@delete');
        });

        /** @see \App\Http\Controllers\PlugsController */
        $router->group(['prefix' => 'plugs'], function () use ($router) {
            $router->get('/{stationId}', 'PlugsController@index');
        });

        /** @see \App\Http\Controllers\ReviewsController */
        $router->group(['prefix' => 'reviews'], function () use ($router) {
            $router->get('/{stationId}', 'ReviewsController@index');
            $router->post('/', 'ReviewsController@create');
        });

        /** @see \App\Http\Controllers\BookingsController */
        $router->group(['prefix' => 'bookings'], function () use ($router) {
            $router->post('/', 'BookingsController@create');
            $router->post('/{userId}', 'BookingsController@index');
            $router->delete('/{id}', 'BookingsController@delete');
            $router->patch('/{id}', 'BookingsController@update');
        });
    });

});
