<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Models\Admin;
use App\Models\Costumer;
use App\Models\Gapoktan;
use App\Models\Poktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => 'required|string',
            'password' => 'required|string'
        ], Helper::messageValidation());

        if ($validator->fails()) {
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED_LOGIN, false, 401);
        }

        $token = app('auth')->attempt($request->only('email', 'password'));
        if ($token) {
            $user = JWTAuth::user();
            $account = null;
            if (Admin::where('user_id', $user['id'])) {
                $account = Admin::where('user_id', $user['id']);
            } elseif (Gapoktan::where('user_id', $user['id'])) {
                $account = Gapoktan::where('user_id', $user['id']);
            } elseif (Poktan::where('user_id', $user['id'])) {
                $account = Poktan::where('user_id', $user['id']);
            } elseif (Costumer::where('user_id', $user['id'])) {
                $account = Costumer::where('user_id', $user['id']);
            }
            return $this->resp([
                'token' => $token,
                'user' => $user,
                'account' => $account->get()
            ]);
        } else {
            return $this->resp(null, Variable::LOGIN_NOT_MATCH, false, 401);
        }
    }

    public function user()
    {
        $user = JWTAuth::user();
        return $this->resp($user);
    }
}
