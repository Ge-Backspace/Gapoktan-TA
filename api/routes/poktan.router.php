<?php

$router -> get('/lihatPoktan', 'PoktanController@lihatPoktan');
$router-> post('/tambahPoktan', 'PoktanController@tambahPoktan');
$router-> post('/ubahPoktan/{id}', 'PoktanController@ubahPoktan');
$router-> delete('/hapusPoktan/{id}', 'PoktanController@hapusPoktan');
