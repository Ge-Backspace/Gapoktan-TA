<?php

namespace App\Http\Controllers;

use App\Helpers\Variable;
use App\Models\GambarProduk;
use Illuminate\Http\Request;

class GambarProdukController extends Controller
{
    public function tambahGambarProduk(Request $request)
    {
        $gambars = $request->only(['gambar']);
        $input = [];

        foreach ($gambars as $key => $gambar) {
            $basePath = base_path('storage/app/public/' . Variable::GPDK);
            $extension = $gambar->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $gambar->clientExtension();
            }
            $fileName = Variable::GPDK . '-' . time() . '.' . $extension;
            $input[] = [
                'file_name' => $fileName,
                'path' => $fileName,
                'size' => $gambar->getSize(),
                'extension' => $extension,
            ];
            $gambar->move($basePath, $fileName);
        }
        GambarProduk::insert($input);
        return $this->resp();
    }
}
