<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'poktan_id', 'uraian', 'tanggal', 'keterangan'
    ];

    public function poktan()
    {
        return $this->belongsTo('App/Models/Poktan');
    }
}
