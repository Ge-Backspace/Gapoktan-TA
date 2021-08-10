<?php
// Route Produk
$router -> get('/lihat_produk', 'ProdukController@lihatProdukGapoktan');
$router -> post('/tambah_produk', 'ProdukController@tambahProduk');
$router -> post('/ubah_produk/{id}', 'ProdukController@ubahProduk');
$router-> delete('/hapus_produk/{id}', 'PoktanController@hapusProduk');

// Route Gambar Produk
$router -> get('/lihat_gambar_produk', 'GambarProdukController@lihatGambarProduk');
$router -> post('/tambah_gambar_produk', 'GambarProdukController@tambahGambarProduk');
