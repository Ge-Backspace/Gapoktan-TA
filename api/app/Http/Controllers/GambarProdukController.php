<?php

namespace App\Http\Controllers;

use App\Helpers\Variable;
use App\Models\GambarProduk;
use Illuminate\Http\Request;

class GambarProdukController extends Controller
{
    public function lihatGambarProduk(Request $request)
    {
        $data = GambarProduk::where('produk_id', $request->produk_id)
        ->orderBy('tandurs.id', 'DESC');
        return $this->getPaginate($data, $request, []);
    }

    public function tambahGambarProduk(Request $request)
    {
        if (!$request->gambars) {
            return $this->resp(null, false, Variable::FAILED, 406);
        }
        return $this->resp($request->gambars);
        // $gambars = $request->only(['gambar']);
        // $input = [];

        // foreach ($gambars as $key => $gambar) {
        //     $basePath = base_path('storage/app/public/' . Variable::GPDK);
        //     $extension = $gambar->getClientOriginalExtension();
        //     if (empty($extension)) {
        //         $extension = $gambar->clientExtension();
        //     }
        //     $fileName = Variable::GPDK . '-' . time() . '.' . $extension;
        //     $input[] = [
        //         'produk_id' => $request->produk_id,
        //         'file_name' => $fileName,
        //         'path' => $fileName,
        //         'size' => $gambar->getSize(),
        //         'extension' => $extension,
        //     ];
        //     $gambar->move($basePath, $fileName);
        // }
        // GambarProduk::insert($input);
        // return $this->resp();
    }
}
