<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePeminjamanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('peminjaman_kode',15);
            $table->integer('buku_id')->unsigned();
            $table->integer('qty')->nullable();
            $table->timestamps();
        });

        Schema::table('peminjaman_detail', function ($table) {
            $table->foreign('peminjaman_kode')->references('kode')->on('peminjaman')->OnDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman_detail');
    }
}
