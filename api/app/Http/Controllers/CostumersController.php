<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\Request;

class CostumersController extends Controller
{
    //
    public function lihatCostumer()
    {
        # code...
        return $this->resp(Costumer::all());
    }

    public function tambahCostumer(Request $request)
    {
        # code...
    }

    public function ubahCostumer(Request $request, $id)
    {
        # code...
    }

    public function hapusCostumer($id)
    {
        # code...
        try {
            Costumer::find($id)->delete();
        } catch (\Throwable $e) {
            return $this->resp(null, $e->getMessage(), false, 404);
        }
        return $this->resp();
    }
}
