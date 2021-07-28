<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Poktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    //
    public function lihatKegiatan(Request $request)
    {
        $data = Kegiatan::join('poktans', 'poktans.id', '=', 'kegiatans.poktan_id')
        ->select(DB::raw('poktans.*, poktan.nama as diisi'))
        ->get();
        return $this->resp($data);
    }

    public function tambahKegiatan(Request $request)
    {
        # code...
        $tambah = $request->only(['poktan_id', 'uraian', 'tanggal', 'keterangan']);
        $poktan = Poktan::find($request->poktan_id);
        if (!$poktan) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = Kegiatan::create($tambah);
            return $this->resp($add);
        }
    }

    public function ubahKegiatan(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['uraian', 'tanggal', 'keterangan']);
        $data = Kegiatan::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'uraian' => $ubah['uraian'],
            'tanggal' => $ubah['tanggal'],
            'keterangan' => $ubah['keterangan'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusKegiatan($id)
    {
        # code...
        $data = Kegiatan::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
