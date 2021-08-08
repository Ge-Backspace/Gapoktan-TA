<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Kegiatan;
use App\Models\Poktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
}
