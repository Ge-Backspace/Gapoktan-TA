<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Poktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoktanController extends Controller
{
    //
    public function lihatPoktan(Request $request)
    {

        return $this->getPaginate(Poktan::where('gapoktan_id', $request->gapoktan_id)->get(), $request, [
            'nama', 'ketua', 'kota', 'alamat'
        ]);
    }

    public function tambahPoktan(Request $request)
    {
        $input = $request->only([
            'email', 'password', 'nama', 'foto', 'gapoktan_id'
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
        $poktan = Poktan::create([
            'user_id' => $user->id,
            'foto_id' => $foto_id,
            'gapoktan_id' => $input['gapoktan_id'],
            'nama' => $input['nama'],
            'ketua' => $input['ketua'],
            'kota' => $input['kota'],
            'alamat' => $input['alamat']
        ]);
        return $this->resp($poktan);
    }

    public function ubahPoktan(Request $request, $id)
    {
        $gapoktan = Poktan::find($id);
        if (!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'nama', 'email', 'gapoktan_id', 'ketua', 'kota', 'alamat', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string|min:4|max:100',
            'email' => 'required',
            'gapoktan_id' => 'required',
            'ketua' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::find($gapoktan->user_id);
        $user->update([
            'email' => $input['email']
        ]);
        $foto_id = 0;
        if(!empty($request->foto)){
            $foto_id = $this->fotoProfil($request->foto);
        }
        if ($foto_id) {
            $gapoktan->update([
                'gapoktan_id' => $input['gapoktan_id'],
                'nama' => $input['nama'],
                'ketua' => $input['ketua'],
                'kota' => $input['kota'],
                'alamat' => $input['alamat'],
                'foto_id' => $foto_id,
            ]);
        } else {
            $gapoktan->update([
                'gapoktan_id' => $input['gapoktan_id'],
                'nama' => $input['nama'],
                'ketua' => $input['ketua'],
                'kota' => $input['kota'],
                'alamat' => $input['alamat'],
            ]);
        }
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
