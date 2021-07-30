<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Admin;
use App\Models\FotoProfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public function lihatAdmin()
    {

        return $this->resp(Admin::all());
    }

    public function tambahAdmin(Request $request)
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
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), 'Failed Add Data', false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password'])
        ]);
        $foto_id = 0;
        if(!empty($request->foto)){
            $type = Variable::USER;
            $basePath = base_path('storage/app/public/' . $type);
            $extension = $request->foto->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $request->foto->clientExtension();
            }
            $fileName = $type . '-' . time() . '.' . $extension;
            $newFile = [
                'file_name' => $fileName,
                'path' => $fileName,
                'size' => $request->foto->getSize(),
                'extension' => $extension,
            ];
            $dataFile = FotoProfil::create($newFile);
            $request->foto->move($basePath, $fileName);
            $foto_id = $dataFile->id;
        }
        $admin = Admin::create([
            'user_id' => $user->id,
            'nama' => $input['nama'],
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
            'email', 'password', 'nama', 'foto'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string|min:4|max:100',
            'email' => 'required',
            'password' => 'required',
            'foto' => 'mimes:jpeg,png,jpg|max:2048'
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), 'Failed Add Data', false, 406);
        }
        $user = User::find($admin->user_id);
        $user->update([
            'email' => $input['email'],
        ]);
        $foto_id = 0;
        if(!empty($request->foto)){
            $type = Variable::USER;
            $basePath = base_path('storage/app/public/' . $type);
            $extension = $request->foto->getClientOriginalExtension();
            if (empty($extension)) {
                $extension = $request->foto->clientExtension();
            }
            $fileName = $type . '-' . time() . '.' . $extension;
            $newFile = [
                'file_name' => $fileName,
                'path' => $fileName,
                'size' => $request->foto->getSize(),
                'extension' => $extension,
            ];
            $dataFile = FotoProfil::create($newFile);
            $request->foto->move($basePath, $fileName);
            $foto_id = $dataFile->id;
        }
        if (!$foto_id) {
            $admin->update([
                'user_id' => $user->id,
                'nama' => $input['nama'],
                'foto_id' => $foto_id
            ]);
        } else {
            $admin->update([
                'user_id' => $user->id,
                'nama' => $input['nama'],
            ]);
        }
        return $this->resp($admin);
    }

    public function hapusAdmin($id)
    {
        try {
            Admin::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
