<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Gapoktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends Controller
{
    //
    public function lihatArtikel(Request $request)
    {
        $data = Artikel::join('gapoktans', 'gapoktans.id', '=', 'artikels.gapoktan_id')
        ->select(DB::raw('artikels.*, gapoktan.nama as published'))
        ->get();
        return $this->resp($data);
    }

    public function tambahArtikel(Request $request)
    {
        # code...
        $tambah = $request->only(['gapoktan_id', 'judul', 'isi']);
        $gapoktan = Gapoktan::find($request->gapoktan_id);
        if (!$gapoktan) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = Artikel::create($tambah);
            return $this->resp($add);
        }
    }

    public function ubahArtikel(Request $request, $id)
    {
        # code...
        $input = $request->only(['judul', 'isi']);
        $data = Artikel::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'judul' => $input['judul'],
            'isi' => $input['isi'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusArtikel($id)
    {
        # code...
        $data = Artikel::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
