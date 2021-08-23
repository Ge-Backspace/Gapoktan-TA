<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Costumer;
use App\Models\FotoProfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CostumersController extends Controller
{
    public function lihatCostumer(Request $request)
    {
        $data = Costumer::join('users', 'users.id', '=', 'costumers.user_id')
        ->leftJoin('foto_profils', 'costumers.foto_id', '=', 'foto_profils.id')
        ->select(DB::raw('costumers.*, users.email, users.aktif, costumers.id as id, foto_profils.nama as nama_foto'))
        ->orderBy('costumers.id', 'DESC');
        return $this->getPaginate($data, $request, ['costumers.nama']);
    }

    public function tambahCostumer(Request $request)
    {
        $input = $request->only([
            'email', 'password', 'nama', 'nomer_hp', 'aktif', 'foto'
        ]);
        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required',
            'nama' => 'required|string',
            'nomer_hp' => 'required|string',
            'aktif' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:5048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password'])
        ]);
        $foto_id = null;
        if($request->file('foto')){
            $foto_id = $this->storeFile(new FotoProfil(),$request->foto, Variable::USER);
        }
        $costumer = Costumer::create([
            'user_id' => $user->id,
            'nama' => $input['nama'],
            'nomer_hp' => $input['nomer_hp'],
            'foto_id' => $foto_id
        ]);
        return $this->resp($costumer);
    }

    public function ubahCostumer(Request $request, $id)
    {
        $costumer = Costumer::find($id);
        if (!$costumer) {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
        $input = $request->only([
            'email', 'nama', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string|min:4|max:100',
            'email' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::find($costumer->user_id);
        $user->update([
            'email' => $input['email'],
        ]);
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        if ($foto_id) {
            $costumer->update([
                'nama' => $input['nama'],
                'foto_id' => $foto_id
            ]);
        } else {
            $costumer->update([
                'user_id' => $user->id,
                'nama' => $input['nama'],
            ]);
        }
        return $this->resp($costumer);
    }

    public function hapusCostumer($id)
    {
        try {
            Costumer::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }
}
