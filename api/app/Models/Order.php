<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'costumer_id',
        'kd_order',
        'address_id',
        'total_harga',
        'status_payment',
        'deskripsi',
        'tanggal_bayar',
        'atas_nama',
        'no_rek',
        'bukti_pembayaran',
        'status_order'
    ];

    public function costumer()
    {
        return $this->belongsTo('App/Models/Costumer', 'costumer_id');
    }

    public function address()
    {
        return $this->belongsTo('App/Models/Address', 'address_id');
    }

    public function orderdetail()
    {
        return $this->hasMany('App/Models/OrderDetail');
    }
}
