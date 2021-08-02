<?php

$router -> get('/lihat_admin', 'AdminsController@lihatAdmin');
$router -> post('/tambah_admin', 'AdminsController@tambahAdmin');
$router -> post('/ubah_admin/{id}', 'AdminsController@ubahAdmin');
$router -> delete('/hapus_admin/{id}', 'AdminsController@hapusAdmin');
