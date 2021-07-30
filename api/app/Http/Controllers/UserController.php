<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function lihatUser()
    {
        # code...
        return $this->resp(User::all());
    }

    public function tambahUser(Request $request)
    {
        # code...
        $tambah = $request->only(['email', 'password']);
        $tambahUser = User::create($tambah);
        return $this->resp($tambahUser);
    }

    public function ubahUser(Request $request, $id)
    {
        # code...
        $ubah = $request->only(['email', 'password']);
        $cariID = User::find($id);
        $ubahData = [
            'email' => $ubah['email'],
            'password' => $ubah['password']
        ];

        try {
            $ubahUser = $cariID->update($ubahData);
        } catch (\Throwable $e) {
            //throw $th;
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp($ubahUser);
    }

    public function hapusUser($id)
    {
        # code...
        try{
            User::find($id)->delete();
        }catch(\Throwable $e){
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
