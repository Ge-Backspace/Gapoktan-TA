<?php

namespace App\Http\Controllers;

use App\Exports\ExportTandurGapoktan;
use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Poktan;
use App\Models\Tandur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\ExportTandurPoktan;
use App\Models\Gapoktan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class TandurController extends Controller
{
    public function lihatTandur(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            if(!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
            $state = true;
        }
        $data = Tandur::where('poktan_id', $state ? $poktan->id : $request->poktan_id)
        ->join('poktans', 'poktans.id', '=', 'tandurs.poktan_id')
        ->select(DB::raw('tandurs.*, poktans.*, poktans.nama as pengisi, tandurs.id as id'))
        ->orderBy('tandurs.id', 'DESC');
        return $this->getPaginate($data, $request, ['tandurs.tanaman', 'tandurs.luas_tandur', 'tandurs.tanggal_tandur', 'tandurs.tanggal_panen']);
    }

    public function lihatSemuaTandur(Request $request)
    {
        return $this->getPaginate(
            Tandur::join('poktans', 'poktans.id', '=', 'tandurs.poktan_id')
            ->select(DB::raw('tandurs.*, poktans.nama as nama_poktan, tandurs.id as id'))
            ->orderBy('tandurs.id', 'DESC')
            , $request, [
            'tandurs.tanaman',
            'tandurs.luas_tandur',
            'tandurs.tanggal_tandur',
            'tandurs.tanggal_panen',
            'poktans.nama'
        ]);
    }

    public function tambahTandur(Request $request)
    {
        $state = false;
        if ($request->user_id) {
            $poktan = Poktan::where('user_id', $request->user_id)->first();
            $state = true;
        } else {
            $poktan = Poktan::find($request->poktan_id);
            if (!$poktan) {
                return $this->resp(null, Variable::NOT_FOUND, false, 406);
            }
        }
        $input = $request->only(['poktan_id', 'tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen']);
        $validator = Validator::make($input, [
            'poktan_id' => 'numeric',
            'tanaman' => 'required',
            'luas_tandur' => 'required|numeric',
            'tanggal_tandur' => 'required|date',
            'tanggal_panen' => 'required|date'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $add = Tandur::create([
            'poktan_id' => $state ? $poktan->id : $input['poktan_id'],
            'tanaman' => $input['tanaman'],
            'luas_tandur' => $input['luas_tandur'],
            'tanggal_tandur' => $input['tanggal_tandur'],
            'tanggal_panen' => $input['tanggal_panen'],
        ]);
        return $this->resp($add);
    }

    public function ubahTandur(Request $request, $id)
    {
        $input = $request->only(['tanaman', 'luas_tandur', 'tanggal_tandur', 'tanggal_panen']);
        $validator = Validator::make($input, [
            'tanaman' => 'required',
            'luas_tandur' => 'required|numeric',
            'tanggal_tandur' => 'required|date',
            'tanggal_panen' => 'required|date'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $data = Tandur::find($id);
        if (!$data) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $inputUpdate = [
            'tanaman' => $input['tanaman'],
            'luas_tandur' => $input['luas_tandur'],
            'tanggal_tandur' => $input['tanggal_tandur'],
            'tanggal_panen' => $input['tanggal_panen']
        ];
        $update = $data->update($inputUpdate);
        return $this->resp($update);
    }

    public function hapusTandur($id)
    {
        $data = Tandur::find($id);
        if(!$data){
            return $this->resp(null, 'failed to delete data because id '.$id.' not found', false, 404);
        }
        $data->delete();
        return $this->resp();
    }

    public function exportTandurPoktan(Request $request)
    {
        $input = $request->only(['state', 'user_id', 'tanggal_awal', 'tanggal_akhir', 'as']);
        $validator = Validator::make($input, [
            'state' => 'required|numeric',
            'user_id' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'as' => 'string',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED_EXPORT, false, 406);
        }
        $state = true;
        if ($input['state'] == 2) {
            $state = false;
        }
        $poktan = Poktan::where('user_id', $input['user_id'])->first();
        $data = Tandur::where('poktan_id', $poktan->id)
        ->whereBetween(
            $state ? 'tanggal_tandur' : 'tanggal_panen',
            [$input['tanggal_awal'], $input['tanggal_akhir']]
        )->get();
        $poktan = Poktan::where('user_id', $input['user_id'])->first();
        $as = \Maatwebsite\Excel\Excel::XLSX;
        $type = 'xlsx';
        if($request->as == 'pdf'){
            $type = 'pdf';
            $as = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        return Excel::download(new ExportTandurPoktan($poktan, $data, $input['tanggal_awal'], $input['tanggal_akhir']), 'Data_Tandur_Poktan_' . $poktan->nama . '-' . Carbon::now().'.' . $type, $as);
    }

    public function exportTandurGapoktan(Request $request)
    {
        $input = $request->only(['state', 'user_id', 'tanggal_awal', 'tanggal_akhir', 'as']);
        $validator = Validator::make($input, [
            'state' => 'required|numeric',
            'user_id' => 'required|numeric',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'as' => 'string',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED_EXPORT, false, 406);
        }
        $state = true;
        if ($input['state'] == 2) {
            $state = false;
        }
        $gapoktan = Gapoktan::where('user_id', $input['user_id'])->first();
        $data = Tandur::join('poktans', 'poktans.id', '=', 'tandurs.poktan_id')
        ->where('poktans.gapoktan_id', $gapoktan->id)
        ->whereBetween(
            $state ? 'tanggal_tandur' : 'tanggal_panen',
            [$input['tanggal_awal'], $input['tanggal_akhir']]
        )
        ->select(DB::raw('tandurs.*, poktans.nama as nama_poktan'))
        ->get();
        $as = \Maatwebsite\Excel\Excel::XLSX;
        $type = 'xlsx';
        if($request->as == 'pdf'){
            $type = 'pdf';
            $as = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        return Excel::download(
            new ExportTandurGapoktan(
                $gapoktan, $data, $input['tanggal_awal'], $input['tanggal_akhir']
            ),
            'Data_Tandur_Poktan_' . $gapoktan->nama . '-' . Carbon::now().'.' . $type, $as
        );
    }
}
