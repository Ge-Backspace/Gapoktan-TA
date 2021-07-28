<?php

$router -> get('/lihatKegiatan', 'KegiatanController@lihatKegiatan');
$router-> post('/tambahKegiatan', 'KegiatanController@tambahKegiatan');
$router-> post('/ubahKegiatan/{id}', 'KegiatanController@ubahKegiatan');
$router-> delete('/hapusKegiatan/{id}', 'KegiatanController@hapusKegiatan');
