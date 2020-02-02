myApp.controller('PenulisBukuController', function($scope,$compile, $http, BASE_URL,DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){

          var URL_LINK = BASE_URL+'/home/master/penulis_buku';
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
              DTColumnBuilder.newColumn('nama').withTitle('Nama Penulis'),
              DTColumnBuilder.newColumn('kebangsaan').withTitle('Kebangsaan'),
              DTColumnBuilder.newColumn('action').withTitle('Opsi').withClass('text-center').notSortable(),
          ];

          $scope.vm.dtInstance = {};
          $scope.vm.reloadData = reloadData;

          function reloadData () {
              $scope.vm.dtInstance.reloadData();
          };

          // end of datatable


          $scope.showModal = function(state,id){
            $scope.reset_form();
            if(state == 'add'){
              $scope.modal_title = "Tambah Data";
              // show btn insert
              $scope.btninsert = true;
              $scope.btnupdate = false;
            }else{
              $scope.modal_title = "Edit Data";
              // show btn update
              $scope.btninsert = false;
              $scope.btnupdate = true;
              $scope.penulis = {};

              $http.get(URL_LINK+'/get_data/'+id)
              .then(function success(e){  
                  var data = e.data;
                  $scope.penulis_id           = data.id;
                  $scope.penulis.nama         = data.nama;
                  $scope.penulis.tahun_lahir  = data.tahun_lahir;
                  $scope.penulis.tempat_lahir = data.tempat_lahir;
                  $scope.penulis.kebangsaan   = data.kebangsaan;
                }, function error(res) {
                console.log(res);
              });
            }
            angular.element('#Modal').modal('show');
            $scope.reset_validation();
          }

        $scope.insertData = function(){
            angular.element('.loading').removeClass('hidden');
            var formdata = $scope.penulis;
            $http.post(URL_LINK+'/insert', formdata)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                angular.element('#Modal').modal('hide');
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
          var formdata = $scope.penulis;
          var id = $scope.penulis_id;
          $http.put(URL_LINK+'/update/'+id, formdata)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                angular.element('#Modal').modal('hide');
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

        $scope.reset_validation = function(){
          angular.element('#div_valid').addClass('hidden');
          angular.element('#ul_valid').empty();
        }

        $scope.reset_form = function(){
          $scope.penulis = {};
          $scope.penulis_id = '';
        }




});
