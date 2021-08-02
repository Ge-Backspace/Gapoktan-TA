<?php

$router -> get('/lihat_order_detail', 'OrderDetailController@lihatOrderDetail');
// $router -> post('/tambah_order_detail', 'OrderDetailController@tambahOrder');
$router -> post('/ubah_order_detail/{id}', 'OrderController@ubahOrderDetail');
// $router -> delete('/hapus_order_detail/{id}', 'OrderController@hapusDetailOrder');
