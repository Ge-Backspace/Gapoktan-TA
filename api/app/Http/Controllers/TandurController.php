<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Poktan;
use App\Models\Tandur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
}
