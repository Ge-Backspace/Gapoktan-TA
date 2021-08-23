<?php

namespace Database\Seeders;

use App\Models\Kategori as ModelKategori;
use Illuminate\Database\Seeder;

class kategori extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelKategori::create([
            'nama' => 'Pertanian'
        ]);

        ModelKategori::create([
            'nama' => 'Benih'
        ]);

        ModelKategori::create([
            'nama' => 'Pupuk'
        ]);

        ModelKategori::create([
            'nama' => 'Buah'
        ]);

        ModelKategori::create([
            'nama' => 'Peternakan'
        ]);
    }
}
