<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\SendsPasswordResetEmails;

class RequestPasswordController extends Controller
{
  use SendsPasswordResetEmails;
  
  public function __construct()
  {
      $this->broker = 'users';
  }
}