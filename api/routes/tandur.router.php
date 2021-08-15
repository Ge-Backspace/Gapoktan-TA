<?php

$router -> get('/lihat_tandur', 'TandurController@lihatTandur');
$router -> get('/lihat_semua_tandur', 'TandurController@lihatSemuaTandur');
$router -> post('/tambah_tandur', 'TandurController@tambahTandur');
$router -> post('/ubah_tandur/{id}', 'TandurController@ubahTandur');
$router -> delete('/hapus_tandur/{id}', 'TandurController@hapusTandur');
$router -> get('/export_tandur_poktan', 'TandurController@exportTandurPoktan');
$router -> get('/export_tandur_gapoktan', 'TandurController@exportTandurGapoktan');
