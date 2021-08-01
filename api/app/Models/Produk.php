<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'gapoktan_id', 'kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status'
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
}
