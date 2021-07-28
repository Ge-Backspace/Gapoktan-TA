<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoProfil extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'foto_id', 'nama'
    ];

    public function admin()
    {
        return $this->hasOne('App/Models/Admin');
    }

    public function gapoktan()
    {
        return $this->hasOne('App/Models/Gapoktan');
    }

    public function poktan()
    {
        return $this->hasOne('App/Models/Poktan');
    }

    public function costumer()
    {
        return $this->hasOne('App/Models/Costumer');
    }
}
