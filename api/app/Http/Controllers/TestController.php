<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $data = test::where('nama', 'Ge')->get();
        return $this->resp($data);
    }

    public function user(Request $request)
    {
        # code...
        $id = $request['id'];
        $data = User::find($id)->join('admins', 'user_id', '=', $id);
        return $this->resp($data);
    }

    public function getProduk(Request $request)
    {
        $data = Produk::join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
        ->select(DB::raw('produks.*, kategoris.nama as nama_kategori'))
        ->get();
        return $this->resp($data);
    }

    public function addProduk(Request $request)
    {
        $input = $request->only(['gapoktan_id','kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status']);
        $kategori = Kategori::find($request->kategori_id);
        if (!$kategori) {
            return $this->resp(null, 'Gagal', false, 406);
        } else {
            $add = Produk::create($input);
            return $this->resp($add);
        }
    }

    public function updateProduk(Request $request, $id)
    {
        # code...
        $input = $request->only(['gapoktan_id','kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status']);
        $data = Produk::find($id);
        if (!$data) {
            return $this->resp(null, 'Gagal', false, 406);
        }
        $inputUpdate = [
            'gapoktan_id' => $input['gapoktan_id'],
            'kategori_id' => $input['kategori_id'],
            'nama' => $input['nama'],
            'kode' => $input['kode'],
            'stok' => $input['stok'],
            'harga' => $input['harga'],
            'deskripsi' => $input['deskripsi'],
            'status' => $input['status'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function deleteProduk($id)
    {
        # code...
        $data = Produk::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.'not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
