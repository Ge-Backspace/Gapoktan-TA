<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokLumbungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_lumbungs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poktan_id')->unsigned();
            $table->date('tahun_banper');
            $table->date('tanggal_lapor');
            $table->string('komoditas');
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('poktan_id')->references('id')->on('poktans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_lumbungs');
    }
}
