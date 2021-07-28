<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('costumer_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->integer('total_harga');
            $table->boolean('status_payment');
            $table->text('deskripsi');
            $table->date('tanggal_bayar');
            $table->string('no_rek');
            $table->string('bukti_pembayaran');
            $table->timestamps();

            $table->foreign('costumer_id')->references('id')->on('costumers');
            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
