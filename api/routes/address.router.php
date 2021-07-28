<?php

$router -> get('/lihatAddress', 'AddressController@lihatAddress');
$router-> post('/tambahAddress', 'AddressController@tambahAddress');
$router-> post('/ubahAddress/{id}', 'AddressController@ubahAddress');
$router-> delete('/hapusAddress/{id}', 'AddressController@hapusAddress');
