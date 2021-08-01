<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'produk_id', 'order_id', 'jumlah', 'harga'
    ];

    public function produk()
    {
        return $this->belongsTo('App/Models/Produk', 'produk_id');
    }

    public function order()
    {
        return $this->belongsTo('App/Models/Order', 'order_id');
    }
}
