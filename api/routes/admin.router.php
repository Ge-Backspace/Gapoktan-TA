<?php

$router -> get('/lihatAdmin', 'AdminsController@lihatAdmin');
$router-> post('/tambahAdmin', 'AdminsController@tambahAdmin');
$router-> post('/ubahAdmin/{id}', 'AdminsController@ubahAdmin');
$router-> delete('/hapusAdmin/{id}', 'AdminsController@hapusAdmin');
