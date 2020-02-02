<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(User::get()->count() == 0){

            User::insert([
            [
              'nip'  => '123456789',
              'password' => bcrypt('muzaki123'),
              'nama'     => 'Muzaki Syahrul',
              'email'     => 'muzakisyahrul100@gmail.com',
              'id_hak_akses' => 1,
              'created_at'      => \Carbon\Carbon::now('Asia/Jakarta'),
              'updated_at'      => \Carbon\Carbon::now('Asia/Jakarta')
            
            ],

            [
              'nip'  => '123456897',
              'password' => bcrypt('udin123'),
              'nama'     => 'Udin',
              'email'     => 'udin@gmail.com',
              'id_hak_akses' => 2,
              'created_at'      => \Carbon\Carbon::now('Asia/Jakarta'),
              'updated_at'      => \Carbon\Carbon::now('Asia/Jakarta')
            
            ],

            ]);

        } else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
