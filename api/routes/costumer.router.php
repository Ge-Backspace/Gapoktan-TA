<?php

$router -> get('/lihat_costumer', 'CostumersController@lihatCostumer');
$router-> post('/tambah_costumer', 'CostumersController@tambahCostumer');
$router-> post('/ubah_costumer/{id}', 'CostumersController@ubahCostumer');
$router-> delete('/hapus_costumer/{id}', 'CostumersController@hapusCostumer');
