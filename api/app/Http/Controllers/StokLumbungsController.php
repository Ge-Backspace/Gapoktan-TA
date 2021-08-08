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
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            if(!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $data = StokLumbung::where('poktan_id', $state ? $poktan->id : $request->poktan_id)
        ->join('poktans', 'poktans.id', '=', 'stok_lumbungs.poktan_id')
        ->select(DB::raw('stok_lumbungs.*, poktans.*, poktans.nama as pengisi, stok_lumbungs.id as id'))
        ->orderBy('stok_lumbungs.id', 'DESC');
        return $this->getPaginate($data, $request, ['stok_lumbungs.tahun_banper', 'stok_lumbungs.tanggal_lapor', 'stok_lumbungs.komoditas', 'stok_lumbungs.jumlah']);
    }

    public function lihatSemuaStokLumbung(Request $request)
    {
        return $this->getPaginate(
            StokLumbung::join('poktans', 'poktans.id', '=', 'stok_lumbungs.poktan_id')
            ->select(DB::raw('stok_lumbungs.*, poktans.nama as nama_poktan, stok_lumbungs.id as id'))
            ->orderBy('stok_lumbungs.id', 'DESC')
            , $request, [
            'stok_lumbungs.tahun_banper',
            'stok_lumbungs.tanggal_lapor',
            'stok_lumbungs.komoditas',
            'stok_lumbungs.jumlah',
            'poktans.nama'
        ]);
    }

    public function tambahStokLumbung(Request $request)
    {
        $input = $request->only(['poktan_id', 'tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $validator = Validator::make($input, [
            'poktan_id' => 'numeric',
            'tanggal_lapor' => 'required|date',
            'tahun_banper' => 'required',
            'jumlah' => 'required|numeric',
            'komoditas' => 'required'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            if(!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $dataPoktan = Poktan::find($state ? $poktan->id : $input['poktan_id']);
        if (!$dataPoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        } else {
            $add = StokLumbung::create([
                'poktan_id' => $state ? $poktan->id : $input['poktan_id'],
                'tahun_banper' => $input['tahun_banper'],
                'tanggal_lapor' => $input['tanggal_lapor'],
                'komoditas' => $input['komoditas'],
                'jumlah' => $input['jumlah']
            ]);
            return $this->resp($add);
        }
    }

    public function ubahStokLumbung(Request $request, $id)
    {
        $input = $request->only(['tahun_banper', 'tanggal_lapor', 'komoditas', 'jumlah']);
        $validator = Validator::make($input, [
            'tanggal_lapor' => 'required|date',
            'tahun_banper' => 'required',
            'jumlah' => 'required|numeric',
            'komoditas' => 'required'
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
