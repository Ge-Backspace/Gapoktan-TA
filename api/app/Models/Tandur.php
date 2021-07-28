<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tandur extends Model
{
    protected $fillable = [
        'poktan_id', 'tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen'
    ];

    public function poktan()
    {
        return $this->belongsTo('App/Models/Poktan');
    }
}
