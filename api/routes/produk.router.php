<?php
$router -> get('/lihat_produk', 'ProdukController@lihatProdukGapoktan');
$router -> post('/tambah_produk', 'ProdukController@tambahProduk');
$router -> post('/ubah_produk/{id}', 'ProdukController@ubahProduk');
$router-> delete('/hapus_produk/{id}', 'PoktanController@hapusProduk');
