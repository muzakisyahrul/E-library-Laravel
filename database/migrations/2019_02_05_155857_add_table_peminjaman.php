<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePeminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->string('kode',15)->primary();
            $table->integer('anggota_id')->unsigned();
            $table->date('tgl_pinjam')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->date('tgl_dikembalikan')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('status_txt',200)->nullable();
            $table->double('denda')->nullable();
            $table->timestamps();
            $table->softdeletes();
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });

        Schema::table('peminjaman', function ($table) {
            $table->foreign('anggota_id')->references('id')->on('anggota')->OnDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}
