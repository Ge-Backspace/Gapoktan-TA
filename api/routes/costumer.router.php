<?php

$router -> get('/lihatCostumer', 'CostumerController@lihatCostumer');
$router-> post('/tambahCostumer', 'CostumerController@tambahCostumer');
$router-> post('/ubahCostumer/{id}', 'CostumerController@ubahCostumer');
$router-> delete('/hapusCostumer/{id}', 'CostumerController@hapusCostumer');
