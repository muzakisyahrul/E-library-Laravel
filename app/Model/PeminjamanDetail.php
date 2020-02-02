<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $table = 'peminjaman_detail';

    public function buku()
    {
        return $this->belongsTo('App\Model\Buku','buku_id');
    }
}
