<?php

namespace App\ExportExcel;

use App\Model\Peminjaman;
use App\Model\Buku;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class PeminjamanDetailExcel implements FromView,ShouldAutoSize
{
    use Exportable;

    public function forDetail($kode)
    {
        $this->kode_pinjam = $kode;
        return $this;
    }

    public function view(): View
    {   
            $query = Peminjaman::where('kode', $this->kode_pinjam)->with(['anggota','details'])->get();
            foreach ($query as $key => $value) {
                foreach ($value->details as $k => $v) {
                   $buku = Buku::select('judul')->where('id',$v->buku_id)->first();
                   $v->judul = $buku->judul;
                }
            } 

        return view('peminjaman.detail_excel', ['data' => $query]);
    }
}
