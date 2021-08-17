<?php
// Route Produk
$router -> get('/lihat_produk', 'ProdukController@lihatProdukGapoktan');
$router -> get('/lihat_semua_produk', 'ProdukController@lihatSemuaProduk');
$router -> get('/lihat_produk_costumer', 'ProdukController@lihatProdukCostumer');
$router -> get('/lihat_semua_produk_costumer', 'ProdukController@lihatSemuaProdukCostumer');
$router -> post('/tambah_produk', 'ProdukController@tambahProduk');
$router -> post('/ubah_produk/{id}', 'ProdukController@ubahProduk');
$router -> post('/ubah_status_produk/{id}', 'ProdukController@changeStatusProduk');
$router-> delete('/hapus_produk/{id}', 'PoktanController@hapusProduk');

// Route Gambar Produk
$router -> get('/lihat_gambar_produk', 'GambarProdukController@lihatGambarProduk');
$router -> post('/tambah_gambar_produk', 'GambarProdukController@tambahGambarProduk');
