<?php

$router -> get('/lihatUser', 'UserController@lihatUser');
$router-> post('/tambahUser', 'UserController@tambahUser');
$router-> post('/ubahUser/{id}', 'UserController@ubahUser');
$router-> delete('/hapusUser/{id}', 'UserController@hapusUser');
