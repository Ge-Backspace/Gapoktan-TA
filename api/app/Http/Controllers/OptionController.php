<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
