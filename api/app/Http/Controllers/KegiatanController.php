<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Kegiatan;
use App\Models\Poktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\ExportKegiatanPoktan;
use App\Exports\ExportKegiatanGapoktan;
use App\Models\Gapoktan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class KegiatanController extends Controller
{
    public function lihatKegiatan(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            $state = true;
        }
        $data = Kegiatan::where('poktan_id', $state ? $poktan->id : $request->poktan_id)
        ->join('poktans', 'poktans.id', '=', 'kegiatans.poktan_id')
        ->select(DB::raw('kegiatans.*, poktans.nama as nama_poktan, kegiatans.id as id'))
        ->orderBy('kegiatans.id', 'DESC');
        return $this->getPaginate($data, $request, ['kegiatans.uraian', 'kegiatans.tanggal', 'kegiatans.keterangan']);
    }

    public function lihatSemuaKegiatan(Request $request)
    {
        return $this->getPaginate(
            Kegiatan::join('poktans', 'poktans.id', '=', 'kegiatans.poktan_id')
            ->select(DB::raw('kegiatans.*, poktans.nama as nama_poktan, kegiatans.id as id'))
            ->orderBy('kegiatans.id', 'DESC')
            , $request, [
            'kegiatans.uraian',
            'kegiatans.tanggal',
            'kegiatans.keterangan',
            'poktans.nama'
        ]);
    }

    public function tambahKegiatan(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            if(!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        } else {
            $poktan = Poktan::find($request->poktan_id);
            if (!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
        }
        $input = $request->only(['poktan_id', 'uraian', 'tanggal', 'keterangan']);
        $validator = Validator::make($input, [
            'poktan_id' => 'numeric',
            'uraian' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        try {
            $add = Kegiatan::create([
                'poktan_id' => $state ? $poktan->id : $input['poktan_id'],
                'uraian' => $input['uraian'],
                'tanggal' => $input['tanggal'],
                'keterangan' => $input['keterangan']
            ]);
            return $this->resp($add);
        } catch (\Throwable $th) {
            return $this->resp(null, $th->getMessage(), false, 406);
        }
    }

    public function ubahKegiatan(Request $request, $id)
    {
        $data = Kegiatan::find($id);
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only(['uraian', 'tanggal', 'keterangan']);
        $validator = Validator::make($input, [
            'uraian' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $inputUpdate = [
            'uraian' => $input['uraian'],
            'tanggal' => $input['tanggal'],
            'keterangan' => $input['keterangan'],
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusKegiatan($id)
    {
        $data = Kegiatan::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }

    public function exportKegiatanPoktan(Request $request)
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
        $data = Kegiatan::where('poktan_id', $poktan->id)
        ->whereBetween(
            'tanggal',
            [$input['tanggal_awal'], $input['tanggal_akhir']]
        )->get();
        $as = \Maatwebsite\Excel\Excel::XLSX;
        $type = 'xlsx';
        if($request->as == 'pdf'){
            $type = 'pdf';
            $as = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        return Excel::download(new ExportKegiatanPoktan($poktan, $data, $input['tanggal_awal'], $input['tanggal_awal']), 'Data_Kegiatan_Gapoktan_' . $poktan->nama . '-' . Carbon::now().'.' . $type, $as);
    }

    public function exportKegiatanGapoktan(Request $request)
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
        $gapoktan = Gapoktan::where('user_id', $input['user_id'])->first();
        $data = Kegiatan::join('poktans', 'poktans.id', '=', 'kegiatans.poktan_id')
        ->where('poktans.gapoktan_id', $gapoktan->id)
        ->whereBetween(
            'kegiatans.tanggal',
            [$input['tanggal_awal'], $input['tanggal_akhir']]
        )
        ->select(DB::raw('kegiatans.*, poktans.nama as nama_poktan'))
        ->get();
        $as = \Maatwebsite\Excel\Excel::XLSX;
        $type = 'xlsx';
        if($request->as == 'pdf'){
            $type = 'pdf';
            $as = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        return Excel::download(new ExportKegiatanGapoktan($gapoktan, $data, $input['tanggal_awal'], $input['tanggal_awal']), 'Data_Kegiatan_Gapoktan_' . $gapoktan->nama . '-' . Carbon::now().'.' . $type, $as);
    }
}
