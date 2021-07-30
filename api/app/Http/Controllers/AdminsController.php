<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    //
    public function lihatAdmin()
    {
        # code...
        return $this->resp(Admin::all());
    }

    public function tambahAdmin(Request $request)
    {
        # code...
    }

    public function ubahAdmin(Request $request, $id)
    {
        # code...
    }

    public function hapusAdmin($id)
    {
        # code...
        try {
            Admin::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
