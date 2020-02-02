@extends('template_admin.app')
@section('open_peminjaman','menu-open')
@section('peminjaman','active')
@section('data_peminjaman','active')
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
<div ng-controller="PeminjamanDataController">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              	<a href="{{Route('peminjaman.form')}}" class="btn btn-primary btn-flat">
              		<i class="fa fa-plus"></i> Tambah
              	</a>
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
          <div ng-controller="DatePickerCtrl">
	        <div class="form-group row">
            <label>Tanggal Dikembalikan:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button class="btn btn-info" ng-click="tanggal_kembali()"><i class="fa fa-calendar"></i></button>
                      </div>
                      <input type="text" class="form-control" id="tanggal_dikembalikan" 
                        uib-datepicker-popup ng-model="input_tanggal_kembali" is-open="popup1.opened" 
                        datepicker-options="dateOptions" close-text="Close" show-button-bar="false" 
                        placeholder="Tanggal Dikembalikan" data-ng-change="on_change_date('date_change')" data-ng-readonly="true" />
                      <div class="input-group-prepend">
                        <button class="btn btn-success square" ng-click="[today_tanggal_kembali(),on_change_date('today')]">Hari Ini</button>
                      </div>
                      <div class="input-group-prepend">
                        <button class="btn btn-danger square" ng-click="[clear_tanggal_kembali(),on_change_date('clear')]"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
            
          </div>
          </div>
          <div data-ng-show="jum_terlambat>0">
            <div class="form-group row">
              <label>Jumlah Keterlambatan:</label>
               <div class="input-group">
              <input type="text" class="form-control" data-ng-model="jum_terlambat" placeholder="Jumlah Keterlambatan" data-ng-change="on_change_jumlah()" data-ng-readonly="true" />
              <div class="input-group-prepend">
                  <button class="btn input-group-text">Hari</button>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label>Denda Per Hari:</label>
              <input type="text" class="form-control" data-ng-model="denda_per_hari" placeholder="Denda Keterlambatan" data-ng-readonly="true" />
            </div>
            <div class="form-group row">
              <label>Total Denda:</label>
              <input type="text" class="form-control" data-ng-model="denda" placeholder="Denda Keterlambatan" data-ng-readonly="true" />
            </div>
          </div>
          </form>
	      </div>
	      <div class="modal-footer">
         <button type="button" class="btn btn-primary" data-ng-click="UpdateData()">
         <i class="fa fa-spinner fa-pulse loading hidden"></i> Simpan
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
<script src="{{asset('/')}}app/controllers/Peminjaman/PeminjamanDataController.js"></script>
@endsection 