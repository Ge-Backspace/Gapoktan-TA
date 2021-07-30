<?php

namespace App\Http\Controllers;

use App\Models\Poktan;
use Illuminate\Http\Request;

class PoktanController extends Controller
{
    //
    public function lihatPoktan()
    {
        # code...
        return $this->resp(Poktan::all());
    }

    public function tambahPoktan(Request $request)
    {
        # code...
    }

    public function ubahPoktan(Request $request, $id)
    {
        # code...
    }

    public function hapusPoktan($id)
    {
        # code...
        try {
            Poktan::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
