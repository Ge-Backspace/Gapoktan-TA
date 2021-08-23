<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Admin;
use App\Models\FotoProfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public function lihatAdmin(Request $request)
    {
        $data = Admin::join('users', 'users.id', '=', 'admins.user_id')
        ->leftJoin('foto_profils', 'admins.foto_id', '=', 'foto_profils.id')
        ->select(DB::raw('admins.*, users.email, users.aktif, admins.id as id, foto_profils.nama as nama_foto'))
        ->orderBy('admins.id', 'DESC');
        return $this->getPaginate($data, $request, ['admins.nama']);
    }

    public function tambahAdmin(Request $request)
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
            'password' => app('hash')->make($input['password']),
            'aktif' => $input['aktif'],
        ]);
        $foto_id = null;
        if($request->file('foto')){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        $admin = Admin::create([
            'user_id' => $user->id,
            'nama' => $input['nama'],
            'nomer_hp' => $input['nomer_hp'],
            'foto_id' => $foto_id
        ]);
        return $this->resp($admin);
    }

    public function ubahAdmin(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'nama', 'nomer_hp', 'aktif', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'nomer_hp' => 'required|string',
            'aktif' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:5048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::find($admin->user_id);
        $user->update([
            'aktif' => $input['aktif'],
        ]);
        $foto_id = null;
        if($request->file('foto')){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        $admin->update([
            'nama' => $input['nama'],
            'nomer_hp' => $input['nomer_hp'],
            'foto_id' => $request->foto ? $foto_id : $admin->foto_id
        ]);
        return $this->resp(['user' => $user, 'admin' => $admin]);
    }

    public function hapusAdmin($id)
    {
        try {
            $admin = Admin::find($id);
            User::find($admin->user_id)->delete();
            $admin->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 406);
        }
        return $this->resp();
    }
}
