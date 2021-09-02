<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'costumer_id', 'nama', 'nomor_hp', 'provinsi_id', 'provinsi', 'type', 'kota_id', 'kota', 'postal_code', 'alamat'
    ];

    public function costumer()
    {
        return $this->belongsTo('App/Models/Costumer', 'costumer_id');
    }

    public function order()
    {
        return $this->hasMany('App/Models/Order');
    }
}
