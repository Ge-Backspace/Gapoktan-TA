<?php

$router -> get('/lihatTandur', 'TandurController@lihatTandur');
$router-> post('/tambahTandur', 'TandurController@tambahTandur');
$router-> post('/ubahTandur/{id}', 'TandurController@ubahTandur');
$router-> delete('/hapusTandur/{id}', 'TandurController@hapusTandur');
