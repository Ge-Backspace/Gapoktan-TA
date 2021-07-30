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

$router->get('/test', 'tests@lihatTest');
$router->post('/addTest', 'tests@inputTest');
$router->delete('/deleteTest', 'tests@deleteTest');
$router->post('/updateTest', 'tests@updateTest');

$router->get('/tests', 'TestController@index');
$router->get('/testUser', 'TestController@user');
$router->get('/testProduk', 'TestController@getProduk');
$router->post('/addProduk', 'TestController@addProduk');
$router->post('/updateProduk/{id}', 'TestController@updateProduk');
$router->delete('deleteProduk/{id}', 'testController@deleteProduk');
