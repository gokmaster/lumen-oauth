<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return "API is working.";
    });

    $router->post('register','UserController@create');
    $router->post('login','UserController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/users/{user_id}', 'UserController@getUserDetils');
    });
});

