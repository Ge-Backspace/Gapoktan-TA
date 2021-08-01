<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterGapoktan extends Model
{
    protected $fillable = [
        'nama', 'email', 'alamat', 'no_hp', 'ketua', 'sk_gapoktan', 'foto_gapoktan', 'foto_ketua'
    ];
}
