<?php

$router -> get('/lihat_kegiatan', 'KegiatanController@lihatKegiatan');
$router -> get('/lihat_semua_kegiatan', 'KegiatanController@lihatSemuaKegiatan');
$router -> post('/tambah_kegiatan', 'KegiatanController@tambahKegiatan');
$router -> post('/ubah_kegiatan/{id}', 'KegiatanController@ubahKegiatan');
$router -> delete('/hapus_kegiatan/{id}', 'KegiatanController@hapusKegiatan');
$router -> get('/export_kegiatan_poktan', 'KegiatanController@exportKegiatanPoktan');
$router -> get('/export_kegiatan_gapoktan', 'KegiatanController@exportKegiatanGapoktan');
