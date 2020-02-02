<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableStokBuku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_buku', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buku_id')->unsigned();
            $table->integer('stok_qty')->default(0);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('stok_buku', function ($table) {
            $table->foreign('buku_id')->references('id')->on('buku')->OnDelete('cascade')->OnUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_buku');
    }
}
