<?php

$router -> get('/lihatAdmin', 'AdminController@lihatAdmin');
$router-> post('/tambahAdmin', 'AdminController@tambahAdmin');
$router-> post('/ubahAdmin/{id}', 'AdminController@ubahAdmin');
$router-> delete('/hapusAdmin/{id}', 'AdminController@hapusAdmin');
