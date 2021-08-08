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
            'nama' => 'Kategori 1'
        ]);

        ModelKategori::create([
            'nama' => 'Kategori 2'
        ]);

        ModelKategori::create([
            'nama' => 'Kategori 3'
        ]);
    }
}
