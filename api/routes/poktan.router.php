<?php

$router -> get('/lihat_poktan', 'PoktanController@lihatPoktan');
$router -> post('/tambah_poktan', 'PoktanController@tambahPoktan');
$router -> post('/ubah_poktan/{id}', 'PoktanController@ubahPoktan');
$router -> delete('/hapus_poktan/{id}', 'PoktanController@hapusPoktan');
