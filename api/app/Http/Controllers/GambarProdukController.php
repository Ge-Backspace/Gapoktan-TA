<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\GambarProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GambarProdukController extends Controller
{
    public function lihatGambarProduk(Request $request)
    {
        $data = GambarProduk::where('produk_id', $request->produk_id)
        ->orderBy('gambar_produks.id', 'DESC');
        return $this->getPaginate($data, $request, []);
    }

    public function tambahGambarProduk(Request $request)
    {
        $input = $request->only(['produk_id', 'gambars']);
        $validator = Validator::make($input, [
            'produk_id' => 'required|numeric',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $gambars = $request->file('gambars');
        if(!$gambars) {
            return $this->resp(null, Variable::FAILED, false, 406);
        }
        $produk = Produk::find($input['produk_id']);
        $inputGambar = [];
        foreach ($gambars as $key => $gambar) {
            $extension = $gambar->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $gambar->clientExtension();
            }
            $basePath = base_path('storage/app/public/' . Variable::GPDK);
            $fileName = Variable::GPDK . '-' . time() . '.' . $extension;
            $inputGambar[] = [
                'produk_id' => $produk->id,
                'nama' => $fileName,
                'path' => $fileName,
                'size' => $gambar->getSize(),
                'extension' => $extension,
            ];
            $gambar->move($basePath, $fileName);
        }
        $add = GambarProduk::insert($inputGambar);
        return $this->resp($add);
    }
}
