<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\FotoProfil;
use App\Models\Gapoktan;
use App\Models\Poktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PoktanController extends Controller
{
    public function lihatPoktan(Request $request)
    {
        $input = $request->only([
            'gapoktan_id', 'user_id'
        ]);
        $validator = Validator::make($input, [
            'gapoktan_id' => 'numeric',
            'user_id' => 'numeric'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($input['user_id']) {
            $gapoktan = Gapoktan::where('user_id', $input['user_id'])->first();
            $state = true;
        }
        return $this->getPaginate(Poktan::join('users', 'users.id', '=', 'poktans.user_id')
        ->leftJoin('foto_profils', 'poktans.foto_id', '=', 'foto_profils.id')
        ->where('poktans.gapoktan_id', $state ? $gapoktan->id : $input['gapoktan_id'])
        ->select(DB::raw('poktans.*, users.email, poktans.id as id, foto_profils.nama as nama_foto'))
        ->orderBy('poktans.id', 'DESC'), $request, [
            'poktans.nama', 'poktans.ketua', 'poktans.kota', 'poktans.alamat', 'users.email'
        ]);
    }

    public function tambahPoktan(Request $request)
    {
        $input = $request->only([
            'email', 'password', 'nama', 'ketua', 'kota', 'alamat', 'foto', 'gapoktan_id'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'email' => 'required',
            'gapoktan_id' => 'numeric',
            'ketua' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'password' => 'required',
            // 'aktif' => 'boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:5048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            $state = true;
        } else {
            $gapoktan = Gapoktan::find($input['gapoktan_id']);
        }
        if(!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password']),
        ]);
        $poktan = Poktan::create([
            'user_id' => $user->id,
            'foto_id' => $foto_id,
            'gapoktan_id' => $state ? $gapoktan->id : $input['gapoktan_id'],
            'nama' => $input['nama'],
            'ketua' => $input['ketua'],
            'kota' => $input['kota'],
            'alamat' => $input['alamat']
        ]);
        return $this->resp($poktan);
    }

    public function ubahPoktan(Request $request, $id)
    {
        $poktan = Poktan::find($id);
        if (!$poktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'nama', 'gapoktan_id', 'ketua', 'kota', 'alamat', 'foto', 'aktif'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required',
            'gapoktan_id' => 'numeric',
            'ketua' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'aktif' => 'boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $state = false;
        if ($request->user_id) {
            $gapoktan = Gapoktan::where('user_id', $request->user_id)->first();
            $state = true;
        } else {
            $gapoktan = Gapoktan::find($input['gapoktan_id']);
        }
        if(!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        $poktan->update([
            'gapoktan_id' => $state ? $gapoktan->id : $input['gapoktan_id'],
            'nama' => $input['nama'],
            'ketua' => $input['ketua'],
            'kota' => $input['kota'],
            'alamat' => $input['alamat'],
            'foto_id' => $foto_id ? $foto_id : null,
        ]);
        return $this->resp($gapoktan);
    }

    public function hapusPoktan($id)
    {
        try {
            Poktan::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
