<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function lihatKategori(Request $request)
    {
        return $this->getPaginate(Kategori::orderBy('id', 'DESC'), $request, ['nama']);
    }

    public function tambahKategori(Request $request)
    {
        $input = $request->only('nama');
        $validator = Validator::make($input, [
            'nama' => 'required|string|min:4|max:100',
            'email' => 'required',
            'password' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $add = Kategori::create($input);
        return $this->resp($add);
    }

    public function ubahKategori(Request $request, $id)
    {
        $input = $request->only(['nama']);
        $validator = Validator::make($input, [
            'nama' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $cari = Kategori::find($id);
        if(!$cari){
            return $this->resp(null, 'kategori tidak ditemukan', false, 404);
        }
        $inputUpdate = ['nama' => $input['nama']];
        $update = $cari->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusKategori($id)
    {
        try {
            Kategori::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
