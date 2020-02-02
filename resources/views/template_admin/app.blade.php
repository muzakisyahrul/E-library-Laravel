<!DOCTYPE html>
<html ng-app="myApp">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('app.name')}} | @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/dist/css/adminlte.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/plugins/iCheck/flat/blue.css">
  <!-- additional css -->
  @yield('add_css')

  <!-- datepicker -->
  <!-- <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/plugins/datepicker/datepicker3.css"> -->
  
</head>
<body class="hold-transition sidebar-mini">
<style type="text/css">
  .sidebar-dark-primary {

    background-color: #1c1991;

}

[class*="sidebar-dark"] .user-panel {

    border-bottom: 1px solid #4e94d2;

}
[class*="sidebar-dark"] .brand-link {

    color: rgba(255,255,255,.8);
    border-bottom: 1px solid #3790df;

}
.card{
  border: 1px solid #8850ec;
}
.card-header {
    background-color: #8850ec;
}
.card-title{
  color: #fff;
}
thead,th{
  background-color: #ddd;
  border-color: #ddd;
}
</style>
@yield('modal')

<div class="wrapper">

  <!-- Navbar -->
  @include('template_admin.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('template_admin.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@yield('content-header')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @yield('breadcumb')
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2019 </strong>
    All rights reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('/')}}js/jquery-3.2.1.min.js"></script>
<script src="{{asset('admin_lte_v3/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 4 -->
<script src="{{asset('admin_lte_v3/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Datatable -->
<script src="{{asset('admin_lte_v3/')}}/plugins/datatables/jquery.dataTables.js"></script>
<!-- SlimScroll -->
<script src="{{asset('admin_lte_v3/')}}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{asset('admin_lte_v3/')}}/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin_lte_v3/')}}/dist/js/adminlte.js"></script>
<!-- AngularJS Library -->
<script src="{{asset('/')}}app/lib/angular/angular.min.js"></script>
<script src="{{asset('/')}}app/lib/angular/angular-datatables.min.js"></script>
<script src="{{asset('/')}}app/lib/angular/ui-bootstrap-tpls-3.0.6.min.js"></script>
<script type="text/javascript">
  var base_url = "{{url('/')}}";
  var myApp = angular.module('myApp',['datatables','ui.bootstrap'])
    .constant('BASE_URL', base_url);
</script>
<!-- additional javascript -->
@yield('add_script')


<!-- datepicker -->
<!-- <script src="{{asset('admin_lte_v3/')}}/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<script type="text/javascript">
    //Datepicker
    // $('.datepicker').datepicker({format: 'yyyy', autoclose:true, todayHighlight: true, todayBtn : 'linked', forceParse: false});
</script>
</body>
</html>
