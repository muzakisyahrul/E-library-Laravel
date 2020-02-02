<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    public function stok()
    {
    	return $this->hasOne('App\Model\StokBuku','buku_id','id');
    }
}
