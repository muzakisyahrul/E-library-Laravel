<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableStokBukuDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('stok_buku_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stok_buku_id')->unsigned();
            $table->integer('tipe_transaksi')->nullable();
            $table->string('tipe_transaksi_txt')->nullable();
            $table->string('kode_transaksi',50)->nullable();
            $table->integer('qty')->default(0);
            $table->timestamps();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_buku_detail');
    }
}
