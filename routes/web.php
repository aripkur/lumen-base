<?php

use Illuminate\Support\Facades\Route;

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

Route::get('login', 'WebController@login');
Route::post('login', 'AuthController@login');
Route::get('/', 'WebController@dashboard');

Route::group(['middleware' => 'rate.limit'], function () {
    Route::get('captcha', 'AuthController@captcha');
});

Route::group(['middleware' => 'auth', 'prefix' => 'api'], function(){
    Route::post('logout', 'AuthController@logout');
    Route::post('user', 'AuthController@user');
});
