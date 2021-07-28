<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'admin@test.com',
            'password' => app('hash')->make('testadmin')
        ]);

        DB::table('users')->insert([
            'email' => 'gapoktan@test.com',
            'password' => app('hash')->make('testgapoktan')
        ]);

        DB::table('users')->insert([
            'email' => 'poktan@test.com',
            'password' => app('hash')->make('testpoktan')
        ]);

        DB::table('users')->insert([
            'email' => 'costumer@test.com',
            'password' => app('hash')->make('testcostumer')
        ]);
    }
}
