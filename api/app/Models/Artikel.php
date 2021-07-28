<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gapoktan_id', 'judul', 'isi'
    ];

    public function gapoktan()
    {
        return $this->belongsTo('App/Models/Gapoktan');
    }
}
