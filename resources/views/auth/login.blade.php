<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-Library | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin_lte_v3/')}}/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>E-</b>Library</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masuk untuk memulai</p>

      @if (session('status'))
      <div class="alert alert-danger">
          {{ session('status') }}
      </div>
      @endif

      <form action="{{Route('p_login')}}" method="POST">
      {{csrf_field()}}
        <div class="input-group mb-3">
        <div class="input-group-append">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
          </div>
          <input type="text" class="form-control" placeholder="Email atau NIP" name="username">
        </div>
        
        <div class="input-group mb-3">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fa fa-lock"></i></span>
          </div>
          <input type="password" class="form-control" placeholder="Password" name="password">
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
          </div>
        </div>
          <!-- /.col -->
      </form>

      
      <!-- /.social-auth-links -->

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('admin_lte_v3/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin_lte_v3/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
