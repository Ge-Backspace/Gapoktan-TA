<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('costumer_id')->unsigned();
            $table->string('nama');
            $table->string('nomor_hp');
            $table->integer('provinsi_id');
            $table->string('provinsi');
            $table->string('type');
            $table->integer('kota_id');
            $table->string('kota');
            $table->string('postal_code');
            $table->string('alamat');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('costumer_id')->references('id')->on('costumers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
