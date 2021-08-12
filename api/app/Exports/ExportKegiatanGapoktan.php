<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportKegiatanPoktan implements FromView
{
    protected $gapoktan, $datas, $tanggal_awal, $tanggal_akhir;

    function __construct($gapoktan, $data, $tanggal_awal, $tanggal_akhir)
    {
        $this->gapoktan = $gapoktan;
        $this->datas = $data;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function view(): View
    {
        return view('kegiatangapoktan', [
            'gapoktan' => $this->gapoktan,
            'datas' => $this->datas,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
        ]);
    }
}
