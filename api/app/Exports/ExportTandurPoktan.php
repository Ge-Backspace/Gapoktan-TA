<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportTandurPoktan implements FromView
{
    // $state : 1 / 2 => 1 = tanggal tandur, 2 = tanggal panen
    protected $poktan, $datas, $tanggal_awal, $tanggal_akhir;

    function __construct($poktan, $data, $tanggal_awal, $tanggal_akhir)
    {
        $this->poktan = $poktan;
        $this->datas = $data;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function view(): View
    {
        return view('tandurpoktan', [
            'poktan' => $this->poktan,
            'datas' => $this->datas,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
        ]);
    }
}
