<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    public function anggota()
    {
        return $this->belongsTo('App\Model\Anggota','anggota_id');
    }

    public function details()
    {
        return $this->hasMany('App\Model\PeminjamanDetail','peminjaman_kode','kode');
    }
}
