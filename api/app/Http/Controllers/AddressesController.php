<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressesController extends Controller
{
    //
    public function lihatAddress(Request $request)
    {
        $data = Address::join('costomers', 'costomer.id', '=', 'Addresses.costomer_id')
        ->select(DB::raw('costomers.*, costomers.nama as nama'))
        ->get();
        return $this->resp($data);
    }

    public function tambahAddress(Request $request)
    {
        # code...
        $tambah = $request->only(['costomer_id', 'nama', 'nomor_hp', 'alamat']);
        $costumer = Costumer::find($request->costumer_id);
        if (!$costumer) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = Address::create($tambah);
            return $this->resp($add);
        }
    }

    public function ubahAddress(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['nama', 'nomor_hp', 'alamat']);
        $data = Address::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'nama' => $ubah['nama'],
            'nomor_hp' => $ubah['nomor_hp'],
            'alamat' => $ubah['alamat'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusAddress($id)
    {
        # code...
        $data = Address::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
