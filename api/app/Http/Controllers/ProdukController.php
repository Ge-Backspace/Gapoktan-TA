<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Gapoktan;
use App\Models\Produk;
use App\Models\ThubnailProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function lihatProdukGapoktan(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            if(!$gapoktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $data = Produk::leftJoin('thubnail_produks', 'produks.id', '=', 'thubnail_produks.produk_id')
        ->join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
        ->where('produks.gapoktan_id', $state ? $gapoktan->id : $request->gapoktan_id)
        ->select(DB::raw('produks.*, thubnail_produks.nama as nama_thumbnail, produks.id as id, kategoris.nama as nama_kategori'))
        ->orderBy('produks.id', 'DESC');
        return $this->getPaginate($data, $request, [
            'produks.kategori_id', 'produks.nama', 'produks.kode', 'produks.stok', 'produks.harga', 'produks.deskripsi', 'produks.status'
        ]);
    }

    public function tambahProduk(Request $request)
    {
        // if ($request->android == true) {
        //     $input = $request->only(['gapoktan_id', 'kategori_id', 'nama', 'stok', 'harga', 'deskripsi', 'foto']);
        //     $validator = Validator::make($input, [
        //         'gapoktan_id' => 'numeric',
        //         'kategori_id' => 'required|numeric',
        //         'nama' => 'required|string',
        //         'stok' => 'required|numeric',
        //         'harga' => 'required|numeric',
        //         'deskripsi' => 'required|string',
        //         'foto' => 'mimes:jpeg,png,jpg|max:5048'
        //     ], Helper::messageValidation());
        //     if ($validator->fails()) {
        //         return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        //     }
        //     if ($request->hasFile('foto')) {
        //     } else {
        //         return $this->resp($request->foto, Variable::FAILED, false, 406);
        //     }
        // } else {
        $input = $request->only(['gapoktan_id', 'kategori_id', 'nama', 'stok', 'harga', 'deskripsi', 'foto']);
        $validator = Validator::make($input, [
            'gapoktan_id' => 'numeric',
            'kategori_id' => 'required|numeric',
            'nama' => 'required|string',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'foto' => 'mimes:jpeg,png,jpg|max:5048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            if(!$gapoktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $kode1 = str_random(8);;
        $kode2 = rand(100,999);
        $produk = Produk::create([
            'gapoktan_id' => $state ? $gapoktan->id : $input['gapoktan_id'],
            'kategori_id' => $input['kategori_id'],
            'nama' => $input['nama'],
            'kode' => $kode1."-".$kode2,
            'stok' => $input['stok'],
            'harga' => $input['harga'],
            'deskripsi' => $input['deskripsi']
        ]);
        if($request->hasFile('foto')){
            try {
                $this->storeFile(new ThubnailProduk(), $request->file('foto'), Variable::TPDK, $produk->id);
            } catch (\Throwable $th) {
                return $this->resp(null, $th);
            }
        }
        return $this->resp($produk);
        // }
    }

    public function ubahProduk(Request $request, $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only(['kategori_id', 'nama', 'kode', 'stok', 'harga', 'deskripsi', 'status', 'foto']);
        $validator = Validator::make($input, [
            'kategori_id' => 'required|numeric',
            'nama' => 'required|string',
            // 'kode' => 'required|string',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'status' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $produk->update([
            'kategori_id' => $input['kategori_id'],
            'nama' => $input['nama'],
            // 'kode' => $input['kode'],
            'stok' => $input['stok'],
            'harga' => $input['harga'],
            'deskripsi' => $input['deskripsi'],
            'status' => $input['status']
        ]);
        if(!empty($request->foto)){
            $this->storeFile(new ThubnailProduk(), $request->foto, Variable::TPDK, $id);
        }
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
