<?php
$router -> get('/option_poktan', 'OptionController@optionPoktan');
$router -> get('/option_kategori', 'OptionController@optionKategori');
$router -> get('/option_address', 'OptionController@optionAddress');
$router -> post('/ongkir', 'OptionController@getOngkir');
$router -> get('/provinsi', 'OptionController@getProvinsi');
$router -> get('/kota/{id}', 'OptionController@getKota');
