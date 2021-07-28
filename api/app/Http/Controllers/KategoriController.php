<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function lihatKategori()
    {
        # code...
        return $this->resp(Kategori::all());
    }

    public function tambahKategori(Request $request)
    {
        # code...
        $tambah = $request->only('nama');
        $tambahKategori = Kategori::create($tambah);
        return $this->resp($tambahKategori);
    }

    public function ubahKategori(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['nama']);
        $cari = Kategori::find($id);
        if(!$cari){
            return $this->resp(null, 'kategori tidak ditemukan', false, 404);
        }
        $ubahData = ['nama' => $ubah['nama']];
        $update = $cari->update($ubahData);
        return $this->resp($update);
    }

    public function hapusKategori($id)
    {
        # code...
        try {
            Kategori::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
