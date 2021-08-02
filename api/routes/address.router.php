<?php

$router -> get('/lihat_address', 'AddressesController@lihatAddress');
$router-> post('/tambah_address', 'AddressesController@tambahAddress');
$router-> post('/ubah_address/{id}', 'AddressesController@ubahAddress');
$router-> delete('/hapus_address/{id}', 'AddressesController@hapusAddress');
