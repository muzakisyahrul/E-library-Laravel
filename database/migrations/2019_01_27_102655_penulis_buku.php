<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PenulisBuku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penulis_buku', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('tahun_lahir',25)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('kebangsaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penulis_buku');
    }
}
