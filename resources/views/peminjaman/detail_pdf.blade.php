<style type="text/css">
.table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 1.5rem;
  background-color: transparent; }
  .table th,
  .table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6; }
  .table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6; }
  .table tbody + tbody {
    border-top: 2px solid #dee2e6; }
  .table .table {
    background-color: #f5f7fa; }

.table-sm th,
.table-sm td {
  padding: 0.3rem; }

.table-bordered {
  border: 1px solid #dee2e6; font-size: 12px }
  .table-bordered th,
  .table-bordered td {
    border: 1px solid #dee2e6; }
  .table-bordered thead th,
  .table-bordered thead td {
    border-bottom-width: 2px; }

    .text-right{
        text-align: right;
    }
    .bold{
        font-weight: 600;
    }

    .toright{
        float:right;
    }
    .text-center{
        text-align: center;
    }

    .content-header{
        border-bottom: 1px solid #000;
        margin-bottom: 5px;
    }
    .info_penjualan{
        margin-bottom: 50px;
    }
    .info_penjualan p{
        margin-bottom: 0px;
        margin-top: 0px;
    }

.column {
  float: left;
  width: 70%;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

.invoice-header{
    border-bottom: 2px solid #000;

}
.invoice-header p{
        margin-bottom: 0px;
        margin-top: 0px;
        font-size:50px;
    }

</style>
@if(count($data)>0)
@foreach($data as $value)
<table width="100%">
        <tr>
            <!-- <td style="text-align:right; vertical-align:middle; color:#FFFFFF;"><img src="images/favicon.png"></td> -->
            <td colspan="2" style="text-align:left; vertical-align:middle;"><h2>E-Library</h2></td>
            <td colspan="2" style="text-align:right; vertical-align:middle;">{{$today}}</td>
        </tr>
    </table>
    
    <table class="table">
        <tr>
            <td colspan="4" style="text-align:center; vertical-align:middle;font-weight:bold">Data Peminjaman {{$value->kode}}</td>
        </tr>
    </table>
<table class="table table-bordered">

        <tr><td colspan="4" class="text-center bold">Informasi Peminjaman</td></tr>

        <tr>
            <th>Kode Peminjaman</th><td>: {{ $value->kode }}</td>
            <th>Nomor Anggota</th><td>: {{ $value->anggota->nomor_anggota }}</td>
        </tr>
        <tr>
            <th>Tanggal Pinjam</th><td>: {{ $value->tgl_pinjam }}</td>
            <th>Nama Peminjam</th><td>: {{ $value->anggota->nama }}</td>
        </tr>
        <tr>
            <th>Tanggal Kembali</th><td>: {{ $value->tgl_kembali }}</td>
            <th>Alamat</th><td>: {{ $value->anggota->alamat }}</td>
        </tr>
        <tr>
            <th>Status</th><td>: {{ $value->status_txt }}</td>
            <th>No Telepon</th><td>: {{ $value->anggota->no_telepon }}</td>
        </tr>
       
        <tr><td colspan="4" class="text-center bold">Buku Yang Dipinjam</td></tr>

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