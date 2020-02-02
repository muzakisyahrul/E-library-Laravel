@extends('template_admin.app')
@section('open_master','menu-open')
@section('master','active')
@section('anggota','active')
@section('title')
{{$title}}
@endsection

@section('content-header')
{{$title}}
@endsection

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{Route('dashboard')}}">Home</a></li>
<li class="breadcrumb-item"><a href="#">Master</a></li>
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
<div ng-controller="AnggotaController">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              	<button class="btn btn-primary btn-flat" data-ng-click="showModal('add',0)">
              		<i class="fa fa-plus"></i> Tambah
              	</button>
              	<button class="btn btn-secondary btn-flat" data-ng-click="vm.reloadData()">
			           <i class="fa fa-refresh"></i> Reload data
			           </button>
            </div>

            <!-- /.card-header -->
            <div class="card-body">

            <div class="col-md-12 hidden" id="alert_message">
              <div class="alert alert-dismissible" id="alert_type">
                <button type="button" class="close" onclick="$('#alert_message').addClass('hidden')">×</button>
                <h5><span data-ng-bind="alert_message"></span></h5>
                    
              </div>
            </div>

            <div class="table-responsive">
              <table datatable="" width="100%" dt-column-defs="vm.dtColumnDefs" dt-options="vm.dtOptions" dt-columns="vm.dtColumns" dt-instance="vm.dtInstance" class="table table-striped table-bordered">
           	  </table>
            </div>
            </div>
           </div>
        </div>
        <!-- /.row -->
        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->

      <!-- Modal -->
	<div id="Modal" class="modal fade" role="dialog" data-backdrop="false">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header bg-primary">
	        <h4 class="modal-title" data-ng-bind="modal_title"></h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
          
              <div class="alert alert-warning alert-dismissible hidden" id="div_valid">
                <button type="button" class="close" data-ng-click="reset_validation()">×</button>
                <h4 class="bold">Terjadi Kesalahan Inputan!</h4>
                <ul id="ul_valid">
                </ul>
              </div>
          <form class="form form-horizontal">
          <div class="form-group row">
            <label class="col-md-3">Nomor:</label>
            <div class="col-md-9">
              <input type="text" class="form-control" data-ng-model="anggota.nomor_anggota" placeholder="Masukkan Nomor Anggota" />
            </div>
          </div>
	        <div class="form-group row">
            <label class="col-md-3">Nama:</label>
            <div class="col-md-9">
              <input type="text" class="form-control" data-ng-model="anggota.nama" placeholder="Masukkan Nama Anggota" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3">No Telepon:</label>
            <div class="col-md-9">
              <input type="text" class="form-control" data-ng-model="anggota.no_telepon" placeholder="Nomor Telepon Anggota" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3">Alamat:</label>
            <div class="col-md-9">
              <textarea class="form-control" data-ng-model="anggota.alamat" placeholder="Alamat Anggota"></textarea>
            </div>
          </div>
          </form>
	      </div>
	      <div class="modal-footer">
	      <button type="button" class="btn btn-primary" data-ng-show="btninsert" data-ng-click="insertData()">
          <i class="fa fa-spinner fa-pulse loading hidden"></i> Submit
        </button>
         <button type="button" class="btn btn-primary" data-ng-show="btnupdate" data-ng-click="UpdateData()">
         <i class="fa fa-spinner fa-pulse loading hidden"></i> Simpan
         </button>
	       <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
	      </div>
	    </div>

	  </div>
	</div>

  <div id="ModalDelete" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title" data-ng-bind="modal_delete_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <span data-ng-bind="delete_message"></span><br/>
          <span data-ng-bind="delete_alert"></span>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-ng-click="DeleteData()">
          <i class="fa fa-spinner fa-pulse loading hidden"></i> Submit
        </button>
         <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>

    </div>
  </div>
  </div>
 </div>
@endsection
@section('add_script')
<!-- datatable -->
<script src="{{asset('admin_lte_v3/')}}/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="{{asset('/')}}app/controllers/Master/AnggotaController.js"></script>
@endsection 