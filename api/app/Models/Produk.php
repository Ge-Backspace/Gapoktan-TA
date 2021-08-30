<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'gapoktan_id', 'kategori_id', 'nama', 'kode', 'berat', 'stok', 'harga', 'deskripsi', 'status'
    ];

    public function gapoktan()
    {
        return $this->belongsTo('App/Models/Gapoktan');
    }

    public function kategori()
    {
        return $this->belongsTo('App/Models/Kategori');
    }

    public function gambarProduk()
    {
        return $this->hasMany('App/Models/GambarProduk');
    }

    public function thubnailProduk()
    {
        return $this->hasOne('App/Models/ThubnailProduk');
    }

    public function orderdetail()
    {
        return $this->hasMany('App/Models/OrderDetail');
    }

    public function chart()
    {
        return $this->hasMany('App/Models/Chart');
    }
}
