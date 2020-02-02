@extends('template_admin.app')
@section('open_peminjaman','menu-open')
@section('peminjaman','active')
@section('form_peminjaman','active')
@section('title')
{{$title}}
@endsection

@section('content-header')
{{$title}}
@endsection

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{Route('dashboard')}}">Home</a></li>
<li class="breadcrumb-item"><a href="#">Peminjaman</a></li>
<li class="breadcrumb-item active">{{$title}}</li>
@endsection

@section('add_css')
<!-- datatable -->
<link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/plugins/datatables/dataTables.bootstrap4.css">
<style type="text/css">
  .hidden{
    display: none;
  }
</style>
@endsection

@section('content')
<div ng-controller="PeminjamanFormController">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form id="formulir">
        <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              	<h3 class="card-title"><i class="fa fa-info-circle"></i> Informasi Peminjaman</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body" id="position_alert">
            <div class="row">
              <div class="col-md-12 hidden" id="alert_message">
                <div class="alert alert-dismissible" id="alert_type">
                  <button type="button" class="close" onclick="$('#alert_message').addClass('hidden')">×</button>
                  <h5><span data-ng-bind="alert_message"></span></h5>
                      
                </div>
              </div>
              <div class="col-md-12">
              <div class="alert alert-warning alert-dismissible" style="display:none" id="div_valid">
                <button type="button" class="close" data-ng-click="reset_validation()">×</button>
                <h4 class="bold">Terjadi Kesalahan Inputan!</h4>
                <ul id="ul_valid">
                </ul>
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row" id="pilih_peminjam">
                  <label class="col-md-4">Pilih Peminjam</label>
                  <div class="col-md-8">
                    : <button class="btn btn-info" data-ng-click="showModal('anggota')"><i class="fa fa-user"></i> Pilih Peminjam</button>
                  </div>
                </div>
                <div class="form-group row" style="display:none" id="nomor_anggota">
                  <label class="col-md-4">Nomor Anggota</label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" data-ng-model="anggota.nomor" data-ng-readonly="true"  />
                      <input type="text" class="form-control" name="id_anggota" data-ng-model="anggota.id" data-ng-hide="true"  />
                      <div class="input-group-prepend">
                        <button class="btn btn-danger square" data-ng-click="reset_anggota()"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
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
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-md-4">Kode Peminjaman</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="kode_pinjam" data-ng-model="kode_pinjam" data-ng-readonly="true" />
                  </div>
                </div>
                <div ng-controller="DatePickerCtrl">
                <div class="form-group row">
                  <label class="col-md-4">Tanggal Peminjaman :</label>
                  <div class="col-md-8">
                    <div class="input-group">
                      <div class="input-group-prepend">
                      <button class="btn input-group-text" ng-click="start_date()"><i class="fa fa-calendar"></i></button>
                    </div>
                      <input type="text" class="form-control" id="tanggal_pinjam"
                        uib-datepicker-popup ng-model="input_start_date" is-open="popup1.opened" 
                        datepicker-options="dateOptions" close-text="Close" show-button-bar="false" 
                        placeholder="Tanggal Pinjam" />
                      <div class="input-group-prepend">
                        <button class="btn btn-success square" ng-click="today_start_date()">Hari Ini</button>
                      </div>
                      <div class="input-group-prepend">
                        <button class="btn btn-danger square" ng-click="clear_start_date()"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4">Tanggal Kembali :</label>
                  <div class="col-md-8">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button class="btn input-group-text" ng-click="end_date()"><i class="fa fa-calendar"></i></button>
                      </div>
                      <input type="text" class="form-control" id="tanggal_kembali" 
                        uib-datepicker-popup ng-model="input_end_date" is-open="popup2.opened" 
                        datepicker-options="dateOptions" close-text="Close" show-button-bar="false" 
                        placeholder="Tanggal Kembali" />
                      <div class="input-group-prepend">
                        <button class="btn btn-success square" ng-click="today_end_date()">Hari Ini</button>
                      </div>
                      <div class="input-group-prepend">
                        <button class="btn btn-danger square" ng-click="clear_end_date()"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                  </div>
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
              <div class="col-md-12 mb-3">
                <button class="btn btn-info" data-ng-click="showModal('buku')"><i class="fa fa-plus"></i> Pilih Buku</button>
              </div>
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-strupped">
                  <thead>
                    <tr>
                      <th width="20%">Kode Buku</th>
                      <th width="40%">Judul Buku</th>
                      <th width="10%" class="text-center">Stok buku</th>
                      <th width="20%">Jumlah Pinjam</th>
                      <th width="10%" class="text-center">Hapus</th>
                    </tr>
                  </thead>
                  <tbody id="tb_buku">
                    <tr id="tr_no_data"><td colspan="5" class="text-center">Anda Belum Menambah Data</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
            <div class="card-footer">
              <div class="col-md-12 text-right">
                <button class="btn btn-primary" data-ng-click="insert_data()"><i class="fa fa-save"></i> SIMPAN</button>
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

<script type="text/ng-template" id="myModal.html">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" data-ng-bind="modal_title"></h4>
          <button type="button" class="close" data-ng-click="cancel()">&times;</button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
              <table datatable="" width="100%" dt-column-defs="vm.dtColumnDefs" dt-options="vm.dtOptions" dt-columns="vm.dtColumns" class="table table-striped table-bordered">
              </table>
            </div>
        </div>
        <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-ng-click="cancel()">Tutup</button>
        </div>
      </div>
</script>
@endsection
@section('add_script')
<!-- datatable -->
<script src="{{asset('admin_lte_v3/')}}/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="{{asset('/')}}app/controllers/Peminjaman/PeminjamanFormController.js"></script>

@endsection 