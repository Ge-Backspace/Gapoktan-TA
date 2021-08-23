<?php
$router -> get('/lihat_profil', 'UserController@lihatProfil');
$router -> post('/ubah_profil/{id}', 'UserController@ubahProfil');
$router->group(['middleware' => 'auth'], function($router){
    $router->get('/users', 'UserController@index');
    $router->get('/user/{id}/show', 'UserController@show');
    $router->delete('/user/delete/{id}', 'UserController@destroy');
    $router->post('/user/store', 'UserController@storeUserEmployee');
    $router->post('/user/update/{id}', 'UserController@update');
    $router->post('/user/{id}/profile', 'UserController@profile');
});
