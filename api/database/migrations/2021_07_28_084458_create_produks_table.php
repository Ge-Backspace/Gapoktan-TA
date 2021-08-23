<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gapoktan_id')->unsigned();
            $table->integer('kategori_id')->unsigned();
            $table->string('nama');
            $table->string('kode');
            $table->integer('berat');
            $table->integer('stok');
            $table->integer('harga');
            $table->text('deskripsi');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('gapoktan_id')->references('id')->on('gapoktans');
            $table->foreign('kategori_id')->references('id')->on('kategoris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produks');
    }
}
