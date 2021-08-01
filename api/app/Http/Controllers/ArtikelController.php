<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Artikel;
use App\Models\Gapoktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function lihatArtikel(Request $request)
    {
        $table = Artikel::join('gapoktans', 'gapoktans.id', '=', 'artikels.gapoktan_id')
        ->select(DB::raw('artikels.*, gapoktan.nama as published'))
        ->orderBy('artikel.id', 'DESC');
        return $this->getPaginate($table, $request, ['artikels.judul', 'artikel.isi']);
    }

    public function tambahArtikel(Request $request)
    {
        $input = $request->only(['gapoktan_id', 'judul', 'isi']);
        $validator = Validator::make($input, [
            'judul' => 'required',
            'isi' => 'required',
            'gapoktan_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $gapoktan = Gapoktan::find($request->gapoktan_id);
        if (!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        } else {
            $add = Artikel::create($input);
            return $this->resp($add);
        }
    }

    public function ubahArtikel(Request $request, $id)
    {
        $input = $request->only(['judul', 'isi']);
        $validator = Validator::make($input, [
            'judul' => 'required',
            'isi' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = Artikel::find($id);
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $inputUpdate = [
            'judul' => $input['judul'],
            'isi' => $input['isi'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusArtikel($id)
    {
        # code...
        $data = Artikel::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
