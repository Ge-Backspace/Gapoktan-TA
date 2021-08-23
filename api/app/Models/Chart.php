<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $fillable = [
        'costumer_id', 'produk_id', 'jumlah', 'status'
    ];

    public function costumer()
    {
        return $this->belongsTo('App/Models/Costumer', 'costumer_id');
    }

    public function produk()
    {
        return $this->belongsTo('App/Models/Produk', 'produk_id');
    }
}
