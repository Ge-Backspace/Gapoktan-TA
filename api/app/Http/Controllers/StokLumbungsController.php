<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Poktan;
use App\Models\StokLumbung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokLumbungsController extends Controller
{
    public function lihatStokLumbung(Request $request)
    {
        $input = $request->only([
            'poktan_id'
        ]);
        $validator = Validator::make($input, [
            'poktan_id' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = StokLumbung::where('poktan_id', $request->poktan_id)
        ->join('poktans', 'poktans.id', '=', 'stok_lumbungs.poktan_id')
        ->select(DB::raw('stok_lumbungs.*, poktans.*, poktans.nama as pengisi'))
        ->orderBy('stok_lumbungs.id', 'DESC');
        return $this->getPaginate($data, $request, ['stok_lumbungs.tahun_banper', 'stok_lumbungs.tanggal_lapor', 'stok_lumbungs.komoditas', 'stok_lumbungs.jumlah']);
    }

    public function tambahStokLumbung(Request $request)
    {
        $input = $request->only(['poktan_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $validator = Validator::make($input, [
            'poktan_id' => 'required|numeric',
            'tanggal_lapor' => 'required',
            'tahun_banper' => 'required|date',
            'jumlah' => 'required|numeric',
            'komoditas' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $poktan = Poktan::find($request->poktan_id);
        if (!$poktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        } else {
            $add = StokLumbung::create($input);
            return $this->resp($add);
        }
    }

    public function ubahStokLumbung(Request $request, $id)
    {
        $input = $request->only(['poktan_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $validator = Validator::make($input, [
            'poktan_id' => 'required|numeric',
            'tanggal_lapor' => 'required',
            'tahun_banper' => 'required|date',
            'jumlah' => 'required|numeric',
            'komoditas' => 'required|numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = StokLumbung::find($id);
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $inputUpdate = [
            'tahun_banper' => $input['tahun_banper'],
            'tanggal_lapor' => $input['tanggal_lapor'],
            'komoditas' => $input['komoditas'],
            'jumlah' => $input['jumlah']
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusStokLumbung($id)
    {
        # code...
        $data = StokLumbung::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }
}
