<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Helpers\Variable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
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

    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        if($request->has('password')){
            $request->request->add([
                'password' => Hash::make($request->password)
            ]);
        }

        return $this->storeData(new User, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ], $request->all());
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string'
        ], Helper::messageValidation());

        if($validator->fails()){
            return $this->resp(Helper::generateErrorMsg($validator->errors()->getMessages()), Variable::FAILED, false, 406);
        }
        $user = auth()->user();
        if(Hash::check($request->old_password, $user->password)){
            $user->password = Hash::make($request->new_password);
            $user->save();
            return $this->resp(null, Variable::SUCCESS);
        }
        return $this->resp(null, Variable::OLD_PASSWORD_INCORRECT, false, 406);

    }
}
