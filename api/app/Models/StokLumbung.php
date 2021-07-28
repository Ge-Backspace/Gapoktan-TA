<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokLumbung extends Model
{
    protected $fillable = [
        'poktan_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah'
    ];

    public function poktan()
    {
        return $this->belongsTo('App/Models/Poktan');
    }
}
