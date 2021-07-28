<?php

$router -> get('/lihatArtikel', 'ArtikelController@lihatArtikel');
$router-> post('/tambahArtikel', 'ArtikelController@tambahArtikel');
$router-> post('/ubahArtikel/{id}', 'ArtikelController@ubahArtikel');
$router-> delete('/hapusArtikel/{id}', 'ArtikelController@hapusArtikel');
