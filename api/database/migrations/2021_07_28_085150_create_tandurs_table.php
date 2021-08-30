<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTandursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tandurs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poktan_id')->unsigned();
            $table->string('tanaman');
            $table->integer('luas_tandur');
            $table->date('tanggal_tandur');
            $table->date('tanggal_panen');
            $table->softDeletes();
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
        Schema::dropIfExists('tandurs');
    }
}
