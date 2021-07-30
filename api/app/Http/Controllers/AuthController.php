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
            $account = [];
            if (Admin::where('user_id', $user['id'])) {
                $account = ['level' => 1, 'account' => 'Admin'];
            } elseif (Gapoktan::where('user_id', $user['id'])) {
                $account = ['level' => 2, 'account' => 'Gapoktan'];
            } elseif (Poktan::where('user_id', $user['id'])) {
                $account = ['level' => 3, 'account' => 'Poktan'];
            } elseif (Costumer::where('user_id', $user['id'])) {
                $account = ['level' => 4, 'account' => 'Costumer'];
            }
            return $this->resp([
                'token' => $token,
                'user' => $user,
                'account' => $account
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
