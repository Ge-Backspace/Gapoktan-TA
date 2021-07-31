<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Costumer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CostumersController extends Controller
{
    public function lihatCostumer(Request $request)
    {
        return $this->getPaginate(Costumer::all(), $request, ['nama']);
    }

    public function tambahCostumer(Request $request)
    {
        $input = $request->only([
            'email', 'password', 'nama', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string|min:4|max:100',
            'email' => 'required',
            'password' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password'])
        ]);
        $foto_id = 0;
        if(!empty($request->foto)){
            $foto_id = $this->fotoProfil($request->foto);
        }
        $costumer = Costumer::create([
            'user_id' => $user->id,
            'nama' => $input['nama'],
            'foto_id' => $foto_id
        ]);
        return $this->resp($costumer);
    }

    public function ubahCostumer(Request $request, $id)
    {
        $costumer = Costumer::find($id);
        if (!$costumer) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
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
        $foto_id = 0;
        if(!empty($request->foto)){
            $foto_id = $this->fotoProfil($request->foto);
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
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
