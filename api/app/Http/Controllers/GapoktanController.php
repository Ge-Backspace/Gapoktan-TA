<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\FotoProfil;
use App\Models\Gapoktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GapoktanController extends Controller
{
    public function lihatGapoktan(Request $request)
    {
        return $this->getPaginate(Gapoktan::orderBy('id', 'DESC'), $request, ['nama', 'ketua', 'kota', 'alamat']);
    }

    public function tambahGapoktan(Request $request)
    {
        $input = $request->only([
            'nama', 'email', 'password', 'ketua', 'kota', 'alamat', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'email' => 'required',
            'password' => 'required',
            'ketua' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password'])
        ]);
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        $gapoktan = Gapoktan::create([
            'user_id' => $user->id,
            'foto_id' => $foto_id,
            'nama' => $input['nama'],
            'ketua' => $input['ketua'],
            'kota' => $input['kota'],
            'alamat' => $input['alamat']
        ]);
        return $this->resp($gapoktan);
    }

    public function ubahGapoktan(Request $request, $id)
    {
        $gapoktan = Gapoktan::find($id);
        if (!$gapoktan) {
            return $this->resp(null, Variable::NOT_FOUND, false, 406);
        }
        $input = $request->only([
            'nama', 'email', 'ketua', 'kota', 'alamat', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'email' => 'required',
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
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER);
        }
        if ($foto_id) {
            $gapoktan->update([
                'nama' => $input['nama'],
                'ketua' => $input['ketua'],
                'kota' => $input['kota'],
                'alamat' => $input['alamat'],
                'foto_id' => $foto_id,
            ]);
        } else {
            $gapoktan->update([
                'nama' => $input['nama'],
                'ketua' => $input['ketua'],
                'kota' => $input['kota'],
                'alamat' => $input['alamat'],
            ]);
            return $this->resp($gapoktan);
        }
    }

    public function hapusGapoktan($id)
    {
        try {
            $gapoktan = Gapoktan::find($id);
            User::find($gapoktan->user_id)->delete();
            $gapoktan->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
