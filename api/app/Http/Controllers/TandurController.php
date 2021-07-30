<?php

namespace App\Http\Controllers;

use App\Models\Poktan;
use App\Models\Tandur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TandurController extends Controller
{
    //
    public function lihatTandur(Request $request)
    {
        $data = Tandur::join('poktans', 'poktans.id', '=', 'tandurs.poktan_id')
        ->select(DB::raw('poktans.*, poktan.nama as diisi'))
        ->get();
        return $this->resp($data);
    }

    public function tambahTandur(Request $request)
    {
        # code...
        $tambah = $request->only(['poktan_id', 'tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen']);
        $poktan = Poktan::find($request->poktan_id);
        if (!$poktan) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = Tandur::create($tambah);
            return $this->resp($add);
        }
    }

    public function ubahTandur(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen']);
        $data = Tandur::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'tanaman' => $ubah['tanaman'],
            'luas_tandur' => $ubah['luas_tandur'],
            'tanggal_tandur' => $ubah['tanggal_tandur'],
            'tanggal_panen' => $ubah['tanggal_panen']
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusTandur($id)
    {
        # code...
        $data = Tandur::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
