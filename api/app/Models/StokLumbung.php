<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokLumbung extends Model
{
    protected $fillable = [
        'tandur_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah'
    ];

    public function tandur()
    {
        return $this->belongsTo('App/Models/Tandur');
    }
}
