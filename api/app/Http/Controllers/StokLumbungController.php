<?php

namespace App\Http\Controllers;

use App\Models\Poktan;
use App\Models\StokLumbung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokLumbungsController extends Controller
{
    //
    public function lihatStokLumbung(Request $request)
    {
        $data = StokLumbung::join('poktans', 'poktans.id', '=', 'stok_lumbungs.poktan_id')
        ->select(DB::raw('poktans.*, poktan.nama as diisi'))
        ->get();
        return $this->resp($data);
    }

    public function tambahStokLumbung(Request $request)
    {
        # code...
        $tambah = $request->only(['poktan_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $poktan = Poktan::find($request->poktan_id);
        if (!$poktan) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = StokLumbung::create($tambah);
            return $this->resp($add);
        }
    }

    public function ubahStokLumbung(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $data = StokLumbung::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'tahun_banper' => $ubah['tahun_banper'],
            'tanggal_lapor' => $ubah['tanggal_lapor'],
            'komoditas' => $ubah['komoditas'],
            'jumlah' => $ubah['jumlah']
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusStokLumbung($id)
    {
        # code...
        $data = StokLumbung::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
