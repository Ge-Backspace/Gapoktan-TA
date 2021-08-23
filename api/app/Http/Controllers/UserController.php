<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Admin;
use App\Models\Costumer;
use App\Models\FotoProfil;
use App\Models\Gapoktan;
use App\Models\Poktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function lihatProfil(Request $request)
    {
        $input = $request->only(['user_id']);
        $validator = Validator::make($input, [
            'user_id' => 'required|numeric',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::find($input['user_id']);
        if (!$user) {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
        $prof1 = Admin::join('users', 'users.id', '=', 'admins.user_id')
        ->leftJoin('foto_profils', 'foto_profils.id', '=', 'admins.foto_id')
        ->where('admins.user_id', $input['user_id'])
        ->select(DB::raw('admins.*, users.*, foto_profils.nama as foto_profil'))->first();
        $prof2 = Gapoktan::join('users', 'users.id', '=', 'gapoktans.user_id')
        ->leftJoin('foto_profils', 'foto_profils.id', '=', 'gapoktans.foto_id')
        ->where('gapoktans.user_id', $input['user_id'])
        ->select(DB::raw('gapoktans.*, users.*, foto_profils.nama as foto_profil'))->first();
        $prof3 = Poktan::join('users', 'users.id', '=', 'poktans.user_id')
        ->leftJoin('foto_profils', 'foto_profils.id', '=', 'poktans.foto_id')
        ->where('poktans.user_id', $input['user_id'])
        ->select(DB::raw('poktans.*, users.*, foto_profils.nama as foto_profil'))->first();
        $prof4 = Costumer::join('users', 'users.id', '=', 'costumers.user_id')
        ->leftJoin('foto_profils', 'foto_profils.id', '=', 'costumers.foto_id')
        ->where('costumers.user_id', $input['user_id'])
        ->select(DB::raw('costumers.*, users.*, foto_profils.nama as foto_profil'))->first();
        if ($prof1) {
            return $this->resp($prof1);
        } elseif ($prof2) {
            return $this->resp($prof2);
        } elseif ($prof3) {
            return $this->resp($prof3);
        } elseif ($prof4) {
            return $this->resp($prof4);
        } else {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
    }

    public function ubahProfil(Request $request, $id)
    {
        $admin = Admin::where('user_id', $id)->first();
        $gapoktan = Gapoktan::where('user_id', $id)->first();
        $poktan = Poktan::where('user_id', $id)->first();
        $costumer = Costumer::where('user_id', $id)->first();
        if ($admin) {
            $profil = $admin;
        } elseif ($gapoktan) {
            $profil = $gapoktan;
        } elseif ($poktan) {
            $profil = $poktan;
        } elseif ($costumer) {
            $input = $request->only(['nama', 'foto', 'nomer_hp']);
            $validator = Validator::make($input, [
                'nama' => 'string',
                'foto' => 'mimes:jpeg,png,jpg|max:5048',
                'nomer_hp' => 'numeric'
            ], Helper::messageValidation());
            if ($validator->fails()) {
                return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
            }
            $foto_id = null;
            if($request->file('foto')){
                $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER, null, $costumer->foto_id);
            }
            $costumer->update([
                'nama' => $request->nama ? $input['nama'] : $costumer->nama,
                'nomer_hp' => $request->nomer_hp ? $input['nomer_hp'] : $costumer->nomer_hp,
                'foto_id' => $foto_id ? $foto_id : null
            ]);
            return $this->resp($costumer);
        } else {
            return $this->resp(null, Variable::NOT_FOUND, false, 404);
        }
    }
}
