<?php

$router -> get('/lihat_order', 'OrderController@lihatOrder');
$router -> get('/lihat_order_gapoktan', 'OrderController@lihatOrderGapoktan');
$router -> get('/lihat_semua_order', 'OrderController@lihatSemuaOrder');
$router -> post('/tambah_order', 'OrderController@tambahOrder');
$router -> post('/ubah_order/{id}', 'OrderController@ubahOrder');
$router -> post('/ubah_status_order/{id}', 'OrderController@ubahStatusOrder');
$router -> delete('/hapus_order/{id}', 'OrderController@hapusOrder');
