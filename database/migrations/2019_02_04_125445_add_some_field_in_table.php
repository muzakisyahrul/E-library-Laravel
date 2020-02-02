<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeFieldInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->softdeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });

        Schema::table('buku', function ($table) {
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });

        Schema::table('rak_buku', function ($table) {
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('kategori_buku', function ($table) {
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('penulis_buku', function ($table) {
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('created_at');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
        });

        Schema::table('buku', function ($table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
        });

        Schema::table('rak_buku', function ($table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('kategori_buku', function ($table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('penulis_buku', function ($table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
