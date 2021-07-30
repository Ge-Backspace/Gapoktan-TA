<?php

namespace App\Http\Controllers;

use App\Models\Gapoktan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GapoktanController extends Controller
{
    //
    public function lihatGapoktan()
    {
        # code...
        return $this->resp(Gapoktan::all());
    }

    public function tambahGapoktan(Request $request)
    {
        # code...
    }

    public function ubahGapoktan(Request $request, $id)
    {
        # code...
    }

    public function hapusGapoktan($id)
    {
        # code...
        try {
            Gapoktan::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
