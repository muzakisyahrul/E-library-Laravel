myApp.controller('BukuController', function($scope,$compile, $rootScope, $http, BASE_URL, $uibModal, DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){

          var URL_LINK = BASE_URL+'/home/master/buku';
          // start datatable
          $scope.vm = {};
          $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
              .withOption('ajax', {
               // Either you specify the AjaxDataProp here
               // dataSrc: 'data',
               url: URL_LINK+'/datatable',
               type: 'GET',
           })

           // or here
           .withDataProp('data')
              .withOption('processing', true)
              .withOption('serverSide', true)
              .withPaginationType('full_numbers')
              .withOption('lengthMenu', [
                  [10, 25, 50, 100],
                  [10, 25, 50, 100]
              ])
              .withDisplayLength(10)
              //this is compile ng-click
              .withOption('createdRow', function(row) {
              // Recompiling so we can bind Angular directive to the DT
              $compile(angular.element(row).contents())($scope);
            })

          $scope.vm.dtColumns = [
              DTColumnBuilder.newColumn('DT_RowIndex').withTitle('No'),
              DTColumnBuilder.newColumn('kode').withTitle('Kode'),
              DTColumnBuilder.newColumn('judul').withTitle('Judul'),
              DTColumnBuilder.newColumn('stok.stok_qty').withTitle('Stok Buku'),
              DTColumnBuilder.newColumn('action').withTitle('Opsi').withClass('text-center').notSortable(),
          ];

          $scope.vm.dtInstance = {};
          $scope.vm.reloadData = reloadData;

          function reloadData () {
              $scope.vm.dtInstance.reloadData();
          };

        $rootScope.$on("ReloadData", function(){
          $scope.vm.dtInstance.reloadData();
         });
          // end of datatable


          $scope.showModal = function(state,id,kategori_id,penulis_id,rak_id){
            var param = new Array();
                param['button'] = state;
                param['id'] = id;
                param['kategori_id'] = kategori_id;
                param['penulis_id'] = penulis_id;
                param['rak_id'] = rak_id;

            var modalInstance = $uibModal.open({
                  animation: true,
                  backdrop:false,
                  ariaLabelledBy: 'modal-content',
                  ariaDescribedBy: 'modal-body',
                  templateUrl: 'myModal.html',
                  controller: 'ModalCtrl',
                  controllerAs: '$ctrl',
                  size:'lg',
                  resolve: {
                      param : function () { 
                          return param;
                       }
                  }
            });
          }


        $scope.ModalDelete = function(id,nama){
          $scope.modal_delete_title = 'Hapus '+nama;
          $scope.delete_message     = 'Anda akan menghapus '+nama+'.';
          $scope.delete_alert       = 'Apakah anda yakin?';
          $scope.delete_id          = id;
          angular.element('#ModalDelete').modal('show');
        }

        $scope.DeleteData = function(){
          angular.element('.loading').removeClass('hidden');
          var id = $scope.delete_id;
          $http.delete(URL_LINK+'/delete/'+id)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                angular.element('#ModalDelete').modal('hide');
                reloadData();
                angular.element('.loading').addClass('hidden');
              }, function error(res) {
                console.log(res);
                angular.element('.loading').addClass('hidden');
          });
        }

        $rootScope.$on("AlertShow", function($even,data){
          $scope.ShowAlert(data);
         });

        $scope.ShowAlert = function(param){
          if(param == 'success'){
            angular.element('#alert_type').addClass('alert-success');
            angular.element('#alert_type').removeClass('alert-warning');
          }else{
            angular.element('#alert_type').removeClass('alert-success');
            angular.element('#alert_type').addClass('alert-warning');
          }
          angular.element('#alert_message').removeClass('hidden');
        }

        

});

myApp.controller('ModalCtrl', function ($uibModalInstance,param,$rootScope,$scope,$http,BASE_URL,$log) {   
        var URL_LINK = BASE_URL+'/home/master/buku';
        
        $scope.getKategori = function(){
          $http.get(URL_LINK+'/get_kategori')
            .then(function success(e){  
                var data = e.data;
                data.unshift({id:0,nama_kategori:'Pilih Kategori'});
                $scope.kategori_list = data;
            }, function error(res) {
                console.log(res);
          });
        }

        $scope.getPenulis = function(){
          $http.get(URL_LINK+'/get_penulis')
            .then(function success(e){  
                var data = e.data;
                data.unshift({id:0,nama:'Pilih Penulis'});
                $scope.penulis_list = data;      
            }, function error(res) {
                console.log(res);
          });
        }

        $scope.getRak = function(){
          $http.get(URL_LINK+'/get_rak')
            .then(function success(e){  
                var data = e.data;
                data.unshift({id:0,nama_rak:'Pilih Rak/Lokasi'});
                $scope.rak_list = data;
            }, function error(res) {
                console.log(res);
          });
        }

        // get data select option
        $scope.getKategori();
        $scope.getPenulis();
        $scope.getRak();
        $scope.buku = {};
        if(param['button'] == 'add'){
           $scope.modal_title = "Tambah Data";
           $scope.label_stok  = "Jumlah Stok:";
           // show btn insert
              $scope.btninsert = true;
              $scope.btnupdate = false;
              $scope.disabled_qty  = false;
              $scope.buku.kategori = 0;
              $scope.buku.penulis = 0;
              $scope.buku.rak = 0;
        }else if(param['button'] == 'edit'){
            $scope.modal_title = "Edit Data";
            $scope.label_stok  = "Stok Saat Ini:";
            // show btn update
              $scope.btninsert = false;
              $scope.btnupdate = true;
              $scope.disabled_qty  = true;

              $http.get(URL_LINK+'/get_data/'+param['id'])
              .then(function success(e){  
                  var data = e.data;
                  $scope.buku_id              = data.id;
                  $scope.buku.kode            = data.kode;
                  $scope.buku.judul           = data.judul;
                  $scope.buku.penerbit        = data.penerbit;
                  $scope.buku.tahun_terbit    = data.tahun_terbit;
                  $scope.buku.isbn            = data.isbn;
                  $scope.buku.jumlah_halaman  = data.halaman;
                  $scope.buku.stok            = data.stok.stok_qty;

                  $scope.buku.kategori        = param['kategori_id'];
                  $scope.buku.penulis         = param['penulis_id'];
                  $scope.buku.rak             = param['rak_id'];
                }, function error(res) {
                console.log(res);
              });       
        }

      $scope.insertData = function(){
            angular.element('.loading').removeClass('hidden');
            var formdata = $scope.buku;
            $http.post(URL_LINK+'/insert', formdata)
            .then(function success(e) {             
                $rootScope.alert_message = e.data.message;
                $rootScope.$emit("AlertShow", e.data.status);
                $scope.ok();
                reloadData();
                angular.element('.loading').addClass('hidden');
              }, function error(res) {
                if(res.status == 422){
                  angular.element('#div_valid').removeClass('hidden');
                  angular.element('#ul_valid').empty();
                  for(er in res.data.errors){
                    angular.element('#ul_valid').append('<li>'+res.data.errors[er][0]+'</li>')
                  }
                }
                angular.element('.loading').addClass('hidden');
            });
        }

        $scope.UpdateData = function(){
          angular.element('.loading').removeClass('hidden');
          var formdata = $scope.buku;
          var id = $scope.buku_id;
          $http.put(URL_LINK+'/update/'+id, formdata)
            .then(function success(e) {             
                $rootScope.alert_message = e.data.message;
                $rootScope.$emit("AlertShow", e.data.status);
                $scope.ok();
                reloadData();
                angular.element('.loading').addClass('hidden');
              }, function error(res) {
                if(res.status == 422){
                  angular.element('#div_valid').removeClass('hidden');
                  angular.element('#ul_valid').empty();
                  for(er in res.data.errors){
                    angular.element('#ul_valid').append('<li>'+res.data.errors[er][0]+'</li>')
                  }
                }
                angular.element('.loading').addClass('hidden');
          });
      }

        $scope.reset_validation = function(){
          angular.element('#div_valid').addClass('hidden');
          angular.element('#ul_valid').empty();
        }



    function reloadData() {  
        $rootScope.$emit("ReloadData", {});
    }
        
    $scope.ok = function () {
      $uibModalInstance.close();
    };

    $scope.cancel = function () {
      $uibModalInstance.dismiss('cancel');
    };


});
