<?php

$router -> get('/lihat_stoklumbung', 'StokLumbungsController@lihatStokLumbung');
$router -> post('/tambah_stoklumbung', 'StokLumbungsController@tambahStokLumbung');
$router -> post('/ubah_stoklumbung/{id}', 'StokLumbungsController@ubahStokLumbung');
$router -> delete('/hapus_stoklumbung/{id}', 'StokLumbungsController@hapusStokLumbung');
