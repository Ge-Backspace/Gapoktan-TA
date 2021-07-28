<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gapoktan extends Model
{
    protected $fillable = [
        'user_id', 'foto_id', 'nama', 'ketua', 'kota', 'alamat'
    ];

    public function user()
    {
        return $this->belongsTo('App/Models/User');
    }

    public function foto()
    {
        return $this->belongsTo('App/Models/FotoProfil');
    }

    public function poktan()
    {
        return $this->hasMany('App/Models/Poktan');
    }

    public function artikel()
    {
        return $this->hasMany('App/Models/Artikel');
    }

    public function produk()
    {
        return $this->hasMany('App/Models/Produk');
    }
}
