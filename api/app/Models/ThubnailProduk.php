<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThubnailProduk extends Model
{
    protected $fillable = [
        'produk_id', 'path', 'nama', 'extension', 'size'
    ];

    public function produk()
    {
        return $this->belongsTo('App/Models/Produk');
    }
}
