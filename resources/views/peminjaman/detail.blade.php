@extends('template_admin.app')
@section('open_peminjaman','menu-open')
@section('peminjaman','active')
@section('title')
{{$title}}
@endsection

@section('content-header')
{{$title}}
@endsection

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{Route('dashboard')}}">Home</a></li>
<li class="breadcrumb-item"><a href="{{Route('peminjaman.data')}}">Peminjaman</a></li>
<li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('add_css')
<style type="text/css">
  .hidden{
    display: none;
  }
</style>
@endsection

@section('content')
<div ng-controller="PeminjamanDetailController">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form id="formulir">
        <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
                <a href="{{Route('peminjaman.data')}}" class="btn btn-primary btn-sm"><i class="fa fa-briefcase"></i> Data Peminjaman</a>
                <a href="{{Route('peminjaman.detail.excel',['kode' => $kode])}}" class="btn btn-success btn-sm ml-2"><i class="fa fa-print"></i> Cetak Excel</a>
                <a href="{{Route('peminjaman.detail.pdf',['kode' => $kode])}}" target="_blank" class="btn btn-warning btn-sm ml-2"><i class="fa fa-print"></i> Cetak PDF</a>
            </div>            
           </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              	<h3 class="card-title"><i class="fa fa-info-circle"></i> Informasi Peminjaman</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body" id="position_alert">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-md-4">Kode Peminjaman</label>
                  <div class="col-md-8">
                    <input type="text" value="{{$kode}}" id="kode_pinjam" style="display:none" />
                    : <span data-ng-bind="pinjam.kode"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Tanggal Peminjaman</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="pinjam.tanggal_pinjam"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Tanggal Kembali</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="pinjam.tanggal_kembali"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Status</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="pinjam.status"></span>
                  </div>
                </div>
                <div style="display:none" id="after_dipinjam">
                  <div class="form-group row">
                    <label class="col-md-4">Tanggal Dikembalikan</label>
                    <div class="col-md-8">
                      : <span data-ng-bind="pinjam.tanggal_dikembalikan"></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-4">Denda</label>
                    <div class="col-md-8">
                      : <span data-ng-bind="pinjam.denda"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-md-4">Nomor Anggota</label>
                  <div class="col-md-6">
                   : <span data-ng-bind="anggota.nomor"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Nama Anggota</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="anggota.nama"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Alamat</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="anggota.alamat"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">No Telepon</label>
                  <div class="col-md-8">
                    : <span data-ng-bind="anggota.no_telepon"></span>
                  </div>
                </div>
              </div>
            </div>
            </div>
           </div>
        </div>
        <!-- /.col-md-12 -->

        <div class="col-12">
          <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-book"></i> Buku Yang Dipinjam</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-strupped">
                  <thead>
                    <tr>
                      <th width="30%">Kode Buku</th>
                      <th width="50%">Judul Buku</th>
                      <th width="20%" class="text-center">Jumlah Pinjam</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr data-ng-repeat="detail in details">
                      <td data-ng-bind="detail.buku.kode"></td>
                      <td data-ng-bind="detail.buku.judul"></td>
                      <td data-ng-bind="detail.qty" class="text-center"></td>
                    </tr>
                  </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
           </div>
        </div>
        <!-- /.col-md-12 -->
        </div>
        </form>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
</div>

@endsection
@section('add_script')
<script src="{{asset('/')}}app/controllers/Peminjaman/PeminjamanDetailController.js"></script>
@endsection 