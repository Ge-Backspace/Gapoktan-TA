<?php

$router -> get('/lihat_kegiatan', 'KegiatanController@lihatKegiatan');
$router -> post('/tambah_kegiatan', 'KegiatanController@tambahKegiatan');
$router -> post('/ubah_kegiatan/{id}', 'KegiatanController@ubahKegiatan');
$router -> delete('/hapus_kegiatan/{id}', 'KegiatanController@hapusKegiatan');
