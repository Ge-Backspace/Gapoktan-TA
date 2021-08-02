<?php

$router -> get('/lihat_artikel', 'ArtikelController@lihatArtikel');
$router -> post('/tambah_artikel', 'ArtikelController@tambahArtikel');
$router -> post('/ubah_artikel/{id}', 'ArtikelController@ubahArtikel');
$router -> delete('/hapus_artikel/{id}', 'ArtikelController@hapusArtikel');
