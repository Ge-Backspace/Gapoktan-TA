<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Costumer;
use App\Models\Kategori;
use App\Models\Poktan;

class OptionController extends Controller
{
    public function optionPoktan()
    {
        return $this->resp(Poktan::all());
    }

    public function optionKategori()
    {
        return $this->resp(Kategori::all());
    }

    public function optionAddress(Request $request)
    {
        $costumer = Costumer::where('user_id', $request->user_id)->first();
        return $this->resp(Address::where('costumer_id', $costumer->id)->get());
    }
}
