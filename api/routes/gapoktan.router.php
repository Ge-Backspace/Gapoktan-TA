<?php

$router -> get('/lihatGapoktan', 'GapoktanController@lihatGapoktan');
$router-> post('/tambahGapoktan', 'GapoktanController@tambahGapoktan');
$router-> post('/ubahGapoktan/{id}', 'GapoktanController@ubahGapoktan');
$router-> delete('/hapusGapoktan/{id}', 'GapoktanController@hapusGapoktan');
