<?php

$router -> get('/lihat_chart', 'ChartController@lihatChart');
$router -> post('/tambah_chart', 'ChartController@tambahChart');
$router -> post('/ubah_jumlah_chart/{id}', 'ChartController@ubahJumlahChart');
$router -> delete('/hapus_chart/{id}', 'ChartController@hapusChart');
