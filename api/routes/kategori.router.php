<?php

$router -> get('/lihatKategori', 'KategoriController@lihatKategori');
$router-> post('/tambahKategori', 'KategoriController@tambahKategori');
$router-> post('/ubahKategori/{id}', 'KategoriController@ubahKategori');
$router-> delete('/hapusKategori/{id}', 'KategoriController@hapusKategori');
