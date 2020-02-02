<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Buku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode',200);
            $table->string('judul',200);
            $table->string('penerbit',200)->nullable();
            $table->string('tahun_terbit',25)->nullable();
            $table->string('isbn',200)->nullable();
            $table->integer('halaman')->nullable();
            $table->integer('kategori_id')->nullable();
            $table->integer('rak_id')->nullable();
            $table->integer('penulis_id')->nullable();
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku');
    }
}
