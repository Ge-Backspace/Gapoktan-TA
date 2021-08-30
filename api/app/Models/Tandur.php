<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tandur extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'poktan_id', 'tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen'
    ];

    public function poktan()
    {
        return $this->belongsTo('App/Models/Poktan');
    }
}
