<?php

$router -> get('/lihat_stok_lumbung', 'StokLumbungsController@lihatStokLumbung');
$router -> get('/lihat_semua_stok_lumbung', 'StokLumbungsController@lihatSemuaStokLumbung');
$router -> post('/tambah_stok_lumbung', 'StokLumbungsController@tambahStokLumbung');
$router -> post('/ubah_stok_lumbung/{id}', 'StokLumbungsController@ubahStokLumbung');
$router -> delete('/hapus_stok_lumbung/{id}', 'StokLumbungsController@hapusStokLumbung');
$router -> get('/export_stok_lumbung_poktan', 'StokLumbungsController@exportStokLumbungPoktan');
