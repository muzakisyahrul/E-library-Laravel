<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StokBuku extends Model
{
    protected $table = 'stok_buku';

    public function buku()
    {
        return $this->belongsTo('App\Model\Buku','buku_id');
    }
}
