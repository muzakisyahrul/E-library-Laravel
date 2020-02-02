myApp.factory('URL_LINK',function(BASE_URL){
  return BASE_URL+'/home/peminjaman';
});

myApp.controller('PeminjamanDetailController', function($scope, $rootScope, $http, URL_LINK){
    var kode = angular.element('#kode_pinjam').val();
    $scope.getDataPinjam = function(kode){
      $http.get(URL_LINK+'/get_pinjam/'+kode)
        .then(function success(e){  
            $scope.setAnggota(e.data.pinjam.anggota);
            $scope.setPinjam(e.data.pinjam);
            $scope.setDetail(e.data.detail)
        }, function error(res) {
            console.log(res);
      });
    }
    $scope.getDataPinjam(kode);

    $scope.setAnggota = function(data){
      $scope.anggota = {};
      $scope.anggota.nomor = data.nomor_anggota;
      $scope.anggota.nama = data.nama;
      $scope.anggota.alamat = data.alamat;
      $scope.anggota.no_telepon = data.no_telepon;
    }

    $scope.setPinjam = function(data){
      $scope.pinjam = {};
      $scope.pinjam.kode  = data.kode;
      $scope.pinjam.status  = data.status_txt;
      $scope.pinjam.tanggal_pinjam  = data.tgl_pinjam;
      $scope.pinjam.tanggal_kembali = data.tgl_kembali;
      if(data.status == 2){
        angular.element('#after_dipinjam').show();
        $scope.pinjam.tanggal_dikembalikan = data.tgl_dikembalikan;
        $scope.pinjam.denda = 'Rp. '+data.denda;
      }
    }

    $scope.setDetail = function(data){
      $scope.details = data;
    }


});