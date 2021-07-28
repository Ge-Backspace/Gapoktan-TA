<?php

namespace App\Http\Controllers;

use App\Models\test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = test::where('nama', 'Ge')->get();
        return $this->resp($data);
    }
}
