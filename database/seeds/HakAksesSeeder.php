<?php

use Illuminate\Database\Seeder;
use App\Model\HakAkses;
class HakAksesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(HakAkses::get()->count() == 0){

            HakAkses::insert([
            [
              'hak_akses' 		=> "Admin",
              'created_at'      => \Carbon\Carbon::now('Asia/Jakarta'),
              'updated_at'      => \Carbon\Carbon::now('Asia/Jakarta')
            
            ],

            [
              'hak_akses' 		=> "Staff",
              'created_at'      => \Carbon\Carbon::now('Asia/Jakarta'),
              'updated_at'      => \Carbon\Carbon::now('Asia/Jakarta')
            
            ],

            ]);

        } else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
