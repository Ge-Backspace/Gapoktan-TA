<?php

$router -> post('/login', 'AuthController@login');
$router -> get('/user', 'AuthController@user');
$router -> get('/account/{id}', 'AuthController@account');
$router -> post('/register', 'AuthController@registerCostumer');
$router -> post('/register_gapoktan', 'AuthController@registerGapoktan');
