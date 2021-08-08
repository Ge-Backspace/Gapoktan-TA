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
        'nama', 'path', 'size', 'extension'
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
