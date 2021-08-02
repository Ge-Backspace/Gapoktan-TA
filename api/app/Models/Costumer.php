<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    protected $fillable = [
        'user_id', 'foto_id', 'nama'
    ];

    public function user()
    {
        return $this->belongsTo('App/Models/User');
    }

    public function foto()
    {
        return $this->belongsTo('App/Models/FotoProfil');
    }

    public function adress()
    {
        return $this->hasMany('App/Models/Address');
    }

    public function order()
    {
        return $this->hasMany('App/Models/Order');
    }

    public function chart()
    {
        return $this->hasMany('App/Models/Chart');
    }
}
