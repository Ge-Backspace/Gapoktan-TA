<?php

$router -> get('/lihat_order', 'OrderController@lihatOrder');
$router -> get('/lihat_order_gapoktan', 'OrderController@lihatOrderGapoktan');
$router -> post('/tambah_order', 'OrderController@tambahOrder');
$router -> post('/ubah_order/{id}', 'OrderController@ubahOrder');
$router -> delete('/hapus_order/{id}', 'OrderController@hapusOrder');
