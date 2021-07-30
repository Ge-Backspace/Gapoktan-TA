<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Costumer;
use App\Models\Gapoktan;
use App\Models\Poktan;
use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;

class user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = ModelsUser::create([
            'email' => 'admin@test.com',
            'password' => app('hash')->make('testadmin')
        ]);

        Admin::create([
            'user_id' => $admin->id,
            'nama' => 'Test Admin'
        ]);

        $gapoktan = ModelsUser::create([
            'email' => 'gapoktan@test.com',
            'password' => app('hash')->make('testgapoktan')
        ]);

        $gapoktanTable = Gapoktan::create([
            'user_id' => $gapoktan->id,
            'nama' => 'Test Gapoktan',
            'ketua' => 'Udin Satya',
            'kota' => 'Test Kota',
            'alamat' => 'Test Alamat Gapoktan'
        ]);

        $poktan = ModelsUser::create([
            'email' => 'poktan@test.com',
            'password' => app('hash')->make('testpoktan')
        ]);

        Poktan::create([
            'user_id' => $poktan->id,
            'gapoktan_id' => $gapoktanTable->id,
            'nama' => 'Test Poktan',
            'ketua' => 'Satya Udin',
            'kota' => 'Test Kota',
            'alamat' => 'Test Alamat Poktan'
        ]);

        $costumer = ModelsUser::create([
            'email' => 'costumer@test.com',
            'password' => app('hash')->make('testcostumer')
        ]);

        Costumer::create([
            'user_id' => $costumer->id,
            'nama' => 'Test Costumer'
        ]);
    }
}
