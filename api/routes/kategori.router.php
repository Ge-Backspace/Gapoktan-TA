<?php

$router -> get('/lihat_kategori', 'KategoriController@lihatKategori');
$router -> post('/tambah_kategori', 'KategoriController@tambahKategori');
$router -> post('/ubah_kategori/{id}', 'KategoriController@ubahKategori');
$router -> delete('/hapus_kategori/{id}', 'KategoriController@hapusKategori');
