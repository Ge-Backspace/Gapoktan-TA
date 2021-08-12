<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Poktan;
use App\Models\StokLumbung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\ExportStokLumbungPoktan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
        $data = StokLumbung::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }

    public function exportStokLumbungPoktan(Request $request)
    {
        $input = $request->only(['user_id', 'tanggal_awal', 'tanggal_akhir', 'as']);
        $validator = Validator::make($input, [
            'user_id' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'as' => 'string',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED_EXPORT, false, 406);
        }
        $poktan = Poktan::where('user_id', $input['user_id'])->first();
        $data = StokLumbung::where('poktan_id', $poktan->id)
        ->whereBetween(
            'tanggal_lapor',
            [$input['tanggal_awal'], $input['tanggal_akhir']]
        )->get();
        $poktan = Poktan::where('user_id', $input['user_id'])->first();
        $as = \Maatwebsite\Excel\Excel::XLSX;
        $type = 'xlsx';
        if($request->as == 'pdf'){
            $type = 'pdf';
            $as = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        return Excel::download(new ExportStokLumbungPoktan($poktan, $data, $input['tanggal_awal'], $input['tanggal_awal']), 'Data_Stok_Lumbung_Poktan_' . $poktan->nama . '-' . Carbon::now().'.' . $type, $as);
    }
}
