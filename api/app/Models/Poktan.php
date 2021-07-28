<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poktan extends Model
{
    protected $fillable = [
        'user_id', 'foto_id', 'gapoktan_id', 'nama', 'ketua', 'kota', 'alamat'
    ];

    public function user()
    {
        return $this->belongsTo('App/Models/User');
    }

    public function foto()
    {
        return $this->belongsTo('App/Models/FotoProfil');
    }

    public function gapoktan()
    {
        return $this->belongsTo('App/Models/Gapoktan');
    }

    public function stokLumbung()
    {
        return $this->hasMany('App/Models/StokLumbung');
    }

    public function kegiatan()
    {
        return $this->hasMany('App/Models/Kegiatan');
    }

    public function tandur()
    {
        return $this->hasMany('App/Models/Tandur');
    }
}
