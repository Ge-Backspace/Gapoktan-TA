<?php

$router -> get('/lihatStokLumbung', 'StokLumbungController@lihatStokLumbung');
$router-> post('/tambahStokLumbung', 'StokLumbungController@tambahStokLumbung');
$router-> post('/ubahStokLumbung/{id}', 'StokLumbungController@ubahStokLumbung');
$router-> delete('/hapusStokLumbung/{id}', 'StokLumbungController@hapusStokLumbung');
