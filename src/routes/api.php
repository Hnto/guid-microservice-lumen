<?php

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

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api'], function ($api) {
    $api->group(['middleware' => 'authorize'], function ($api) {
        //Show all available resources
        $api->get('/', 'IndexController@init');
        $api->get('/resources', 'IndexController@init');

        //Guids
        $api->put('/guids', 'IndexController@init');
        $api->post('/guids', 'IndexController@init');
        $api->get('/guids/{guid}', 'IndexController@init');
        $api->get('/guids', 'IndexController@init');
    });

    $api->group(['middleware' => 'authenticate'], function ($api) {
        $api->get('/authenticate', 'IndexController@init');
    });
});