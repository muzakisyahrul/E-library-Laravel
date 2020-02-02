    <style>
        .table-bordered td, .table-bordered th{
           border : 1px solid black !important;
        }
        .text-center{
            text-align: center;
        }
    </style>
    
@if(count($data)>0)
@foreach($data as $value)
<table>
        <tr>
            <!-- <td style="text-align:right; vertical-align:middle; color:#FFFFFF;"><img src="images/favicon.png"></td> -->
            <td colspan="2" style="text-align:left; vertical-align:middle;"><h2>E-Library</h2></td>
            <td colspan="2" style="text-align:right; vertical-align:middle;">{{$today}}</td>
        </tr>
    </table>
    
    <table class="table">
        <tr>
            <td colspan="4" style="text-align:center; vertical-align:middle;"><h3>Data Peminjaman {{$value->kode}}</h3></td>
        </tr>
    </table>
<table class="table table-bordered">

        <tr><td colspan="4" class="text-center"><h3>Informasi Peminjaman</h3></td></tr>

        <tr>
            <th>Kode Peminjaman</th><td>:{{ $value->kode }}</td>
            <th>Nomor Anggota</th><td>:{{ $value->anggota->nomor_anggota }}</td>
        </tr>
        <tr>
            <th>Tanggal Pinjam</th><td>:{{ $value->tgl_pinjam }}</td>
            <th>Nama Peminjam</th><td>:{{ $value->anggota->nama }}</td>
        </tr>
        <tr>
            <th>Tanggal Kembali</th><td>:{{ $value->tgl_kembali }}</td>
            <th>Alamat</th><td>:{{ $value->anggota->alamat }}</td>
        </tr>
        <tr>
            <th>Status</th><td>:{{ $value->status_txt }}</td>
            <th>No Telepon</th><td>:{{ $value->anggota->no_telepon }}</td>
        </tr>
       
        <tr><td colspan="4" class="text-center"><h3>Buku Yang Dipinjam</h3></td></tr>

        <tr>
        <th colspan="2">Judul Buku</th>
        <th colspan="2">Jumlah Pinjam</th>
        </tr>
        @foreach($value->details as $detail_value)
        <tr>
            <td colspan="2">{{ $detail_value->judul }}</td>
            <td colspan="2">{{ $detail_value->qty }}</td>
        </tr>
        @endforeach
        
</table>
@endforeach
@else
<table border="1">
    <tr>
        <td colspan="6">Data Tidak Ada</td>
    </tr>
</table>
@endif