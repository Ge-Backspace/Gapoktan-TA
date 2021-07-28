<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'costumer_id', 'nama', 'nomor_hp', 'alamat'
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
