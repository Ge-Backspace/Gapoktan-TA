<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\FotoProfil;
use App\Models\Gapoktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GapoktanController extends Controller
{
    public function lihatGapoktan(Request $request)
    {
        return $this->getPaginate(Gapoktan::orderBy('id', 'DESC'), $request, ['nama', 'ketua', 'kota', 'alamat']);
    }

    public function lihatSemuaGapoktan(Request $request)
    {
        $data = Gapoktan::join('users', 'users.id', '=', 'gapoktans.user_id')
        ->leftJoin('foto_profils', 'gapoktans.foto_id', '=', 'foto_profils.id')
        ->select(DB::raw('gapoktans.*, users.email, users.aktif , gapoktans.id as id, foto_profils.nama as nama_foto'))
        ->orderBy('gapoktans.id', 'DESC');
        return $this->getPaginate($data, $request, ['gapoktans.nama', 'gapoktans.ketua', 'gapoktans.kota', 'gapoktans.alamat', 'users.email']);
    }

    public function tambahGapoktan(Request $request)
    {
        $input = $request->only([
            'nama', 'email', 'password', 'ketua', 'kota', 'aktif', 'alamat', 'foto', 'nomer_hp'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'email' => 'required',
            'password' => 'required',
            'ketua' => 'required',
            'nomer_hp' => 'required',
            'aktif' => 'required|boolean',
            'kota' => 'required',
            'alamat' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'aktif' => $input['aktif'],
            'password' => app('hash')->make($input['password'])
        ]);
        $foto_id = null;
        if($request->file('foto')){
            $file = $request->file('foto');
            $type = Variable::USER;
            $basePath = base_path('storage/app/public/' . $type);
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $file->clientExtension();
            }
            $fileName = $type . '-' . time() . '.' . $extension;
            $newFile = [
                'nama' => $fileName,
                'path' => $fileName,
                'size' => $file->getSize(),
                'extension' => $extension,
            ];
            $foto = FotoProfil::create($newFile);
            $file->move($basePath, $fileName);
            $foto_id = $foto->id;
        }
        $gapoktan = Gapoktan::create([
            'user_id' => $user->id,
            'foto_id' => $foto_id,
            'nama' => $input['nama'],
            'nomer_hp' => $input['nomer_hp'],
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
            'nama', 'ketua', 'kota', 'alamat', 'foto', 'aktif'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'ketua' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'aktif' => 'required|boolean',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::find($gapoktan->user_id);
        $user->update([
            'aktif' => $input['aktif']
        ]);
        $foto_id = null;
        if(!empty($request->foto)){
            $foto_id = $this->storeFile(new FotoProfil(), $request->foto, Variable::USER, null, $gapoktan->foto_id);
        }
        if ($foto_id) {
            $gapoktan->update([
                'nama' => $input['nama'],
                'ketua' => $input['ketua'],
                'kota' => $input['kota'],
                'alamat' => $input['alamat'],
                'foto_id' => $foto_id,
            ]);
            return $this->resp($gapoktan);
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
