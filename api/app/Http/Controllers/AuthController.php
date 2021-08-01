<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Admin;
use App\Models\Costumer;
use App\Models\Gapoktan;
use App\Models\Poktan;
use App\Models\RegisterGapoktan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login (Request $request){

        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => 'required|string',
            'password' => 'required|string'
        ], Helper::messageValidation());

        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED_LOGIN, false, 401);
        }

        $token = app('auth')->attempt($request->only('email', 'password'));
        if($token){
            return $this->resp([
                'token' => $token,
                'user' => JWTAuth::user()
            ]);
        }

        return $this->resp(null, Variable::LOGIN_NOT_MATCH, false, 401);
    }

    public function user()
    {
        $user = JWTAuth::user();
        return $this->resp($user);
    }

    public function account($id)
    {
        $account = 0;
        if (Admin::where('user_id', $id)) {
            $account = 1;
        } elseif (Gapoktan::where('user_id', $id)) {
            $account = 2;
        } elseif (Poktan::where('user_id', $id)) {
            $account = 3;
        } elseif (Costumer::where('user_id', $id)) {
            $account = 4;
        }
        return $this->resp($account);
    }

    public function registerCostumer(Request $request)
    {
        $input = $request->only([
            'nama', 'email', 'password'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = User::create([
            'email' => $input['email'],
            'password' => app('hash')->make($input['password']),
            'aktif' => true
        ]);
        $costumer = Costumer::create([
            'user_id' => $user->id,
            'nama' => $input['nama'],
        ]);
        return $this->resp($costumer);
    }

    public function registerGapoktan(Request $request)
    {
        $input = $request->only([
            'nama', 'email', 'alamat', 'no_hp', 'ketua', 'sk_gapoktan', 'foto_gapoktan', 'foto_ketua'
        ]);
        $validator = Validator::make($input, [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|string',
            'no_hp' => 'required|string',
            'ketua' => 'required|string',
            'sk_gapoktan' => 'required',
            'foto_gapoktan' => 'mimes:jpeg,png,jpg|max:2048',
            'foto_ketua' => 'mimes:jpeg,png,jpg|max:2048',
        ], Helper::messageValidation());
        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $file1 = $request->sk_gapoktan;
        $basePath = base_path('storage/app/public/' . Variable::RGPK);
        $extension1 = $file1->getClientOriginalExtension();
        $extension2 = $request->foto_gapoktan->getClientOriginalExtension();
        $extension3 = $request->foto_ketua->getClientOriginalExtension();
        if (empty($extension1)) {
            $extension1 = $$request->sk_gapoktan->clientExtension();
        } if (empty($extension2)) {
            $extension2 = $$request->foto_gapoktan->clientExtension();
        } if (empty($extension2)) {
            $extension3 = $$request->foto_ketua->clientExtension();
        }
        $fileName1 = Variable::RGPK . '-' . time() . '.' . $extension;
        $fileName2 = Variable::RGPK . '-' . time() . '.' . $extension;
        $fileName3 = Variable::RGPK . '-' . time() . '.' . $extension;
        $file->move($basePath, $fileName);
        RegisterGapoktan::create([
            'nama' => $input['nama'],
            'email' => $input['email'],
            'alamat' => $input['alamat'],
            'no_hp' => $input['no_hp'],
            'ketua' => $input['ketua'],
            'sk_gapoktan' => ,
        ]);
    }
}
