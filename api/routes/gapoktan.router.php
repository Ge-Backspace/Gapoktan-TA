<?php

$router -> get('/lihat_gapoktan', 'GapoktanController@lihatGapoktan');
$router -> post('/tambah_gapoktan', 'GapoktanController@tambahGapoktan');
$router -> post('/ubah_gapoktan/{id}', 'GapoktanController@ubahGapoktan');
$router -> delete('/hapus_gapoktan/{id}', 'GapoktanController@hapusGapoktan');
