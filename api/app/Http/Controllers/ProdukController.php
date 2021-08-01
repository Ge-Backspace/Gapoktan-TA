<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Produk;
use App\Models\ThubnailProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function lihatProdukGapoktan(Request $request)
    {
        return $this->getPaginate(Produk::where('gapoktan_id', $request->gapoktan_id)->orderBy('id', 'DESC'), $request, [
            'kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status'
        ]);
    }

    public function tambahProduk(Request $request)
    {
        $input = $request->only(['gapoktan_id', 'kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status', 'foto']);
        $validator = Validator::make($input, [
            'gapoktan_id' => 'required|numeric',
            'kategori_id' => 'required|numeric',
            'nama' => 'required|string',
            'kode' => 'required|string',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'status' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        if(!empty($request->foto)){
            $this->storeFile(new ThubnailProduk(), $request->foto, Variable::TPDK);
        }
        $produk = Produk::create([
            'gapoktan_id' => $input['gapoktan_id'],
            'kategori_id' => $input['kategori_id'],
            'nama' => $input['nama'],
            'kode' => $input['kode'],
            'stok' => $input['stok'],
            'harga' => $input['harga'],
            'deskripsi' => $input['deskripsi'],
            'status' => $input['status']
        ]);
        return $this->resp($produk);
    }

    public function ubahProduk(Request $request, $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only(['gapoktan_id', 'kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status', 'foto']);
        $validator = Validator::make($input, [
            'gapoktan_id' => 'required|numeric',
            'kategori_id' => 'required|numeric',
            'nama' => 'required|string',
            'kode' => 'required|string',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'status' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        if(!empty($request->foto)){
            $this->storeFile(new ThubnailProduk(), $request->foto, Variable::TPDK);
        }
        $produk->update([
            'gapoktan_id' => $input['gapoktan_id'],
            'kategori_id' => $input['kategori_id'],
            'nama' => $input['nama'],
            'kode' => $input['kode'],
            'stok' => $input['stok'],
            'harga' => $input['harga'],
            'deskripsi' => $input['deskripsi'],
            'status' => $input['status']
        ]);
        return $this->resp($produk);
    }

    public function hapusProduk($id)
    {
        try {
            Produk::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
