<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;
}