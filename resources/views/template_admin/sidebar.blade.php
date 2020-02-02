<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{Route('dashboard')}}" class="brand-link">
      <img src="{{asset('admin_lte_v3/')}}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('/')}}{{'images'.Auth::user()->photo}}" class="img-circle elevation-2" style="width:35px;height:35px" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->nama}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
                <a href="{{Route('dashboard')}}" class="nav-link @yield('dashboard')">
                  <i class="fa fa-dashboard nav-icon"></i>
                  <p>Dashboard</p>
                </a>
          </li>
           <li class="nav-item">
                <a href="{{Route('peminjaman.form')}}" class="nav-link @yield('form_peminjaman')">
                  <i class="fa fa-wpforms  nav-icon"></i>
                  <p>Peminjaman</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('peminjaman.data')}}" class="nav-link @yield('data_peminjaman')">
                  <i class="fa fa-address-book nav-icon"></i>
                  <p>Data Peminjaman</p>
                </a>
              </li>
          <li class="nav-item has-treeview @yield('open_master')">
            <a href="#" class="nav-link @yield('master')">
              <i class="nav-icon fa fa-briefcase"></i>
              <p>
                Data Master
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(Auth::user()->id_hak_akses == 1)
              <li class="nav-item">
                <a href="{{Route('petugas.data')}}" class="nav-link @yield('petugas')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Petugas</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="{{Route('anggota.data')}}" class="nav-link @yield('anggota')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Anggota</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('buku.data')}}" class="nav-link @yield('buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Buku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('kategori.data')}}" class="nav-link @yield('kategori_buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Kategori Buku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('rak_buku.data')}}" class="nav-link @yield('rak_buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Rak / Lokasi Buku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('penulis_buku.data')}}" class="nav-link @yield('penulis_buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Penulis Buku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{Route('stok_buku.data')}}" class="nav-link @yield('stok_buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Stok Buku</p>
                </a>
              </li>
              @if(Auth::user()->id_hak_akses == 1)
              <li class="nav-item">
                <a href="{{Route('denda.data')}}" class="nav-link @yield('denda_buku')">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Denda Keterlambatan</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
        </ul>
        
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>