<?php

$router -> get('/lihat_artikel', 'ArtikelController@lihatArtikel');
$router -> get('/lihat_semua_artikel', 'ArtikelController@lihatSemuaArtikel');
$router -> post('/tambah_artikel', 'ArtikelController@tambahArtikel');
$router -> post('/ubah_artikel/{id}', 'ArtikelController@ubahArtikel');
$router -> delete('/hapus_artikel/{id}', 'ArtikelController@hapusArtikel');
