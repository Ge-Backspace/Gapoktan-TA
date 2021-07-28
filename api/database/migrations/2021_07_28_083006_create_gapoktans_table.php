<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGapoktansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gapoktans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('foto_id')->unsigned()->nullable();
            $table->string('nama');
            $table->string('ketua');
            $table->string('kota');
            $table->string('alamat');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('foto_id')->references('id')->on('foto_profils')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gapoktans');
    }
}
