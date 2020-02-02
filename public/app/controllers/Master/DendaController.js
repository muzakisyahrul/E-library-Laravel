myApp.controller('DendaController', function($scope,$compile, $http, BASE_URL,DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){

        var URL_LINK = BASE_URL+'/home/master/denda';

        // get all data
         $scope.getData = function(){
              $http.get(URL_LINK+'/get_denda')
              .then(function success(e){ 
                  $scope.daftar_denda  = e.data;
                }, function error(res) {
                console.log(res);
              });
         }

         $scope.no_data = 'Tidak Ada Data';

         $scope.getData();


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
              $scope.denda = {};

              $http.get(URL_LINK+'/get_denda/'+id)
              .then(function success(e){  
                  var data = e.data;
                  $scope.denda_id  = data.id;
                  $scope.denda.nominal  = data.nominal;
                }, function error(res) {
                console.log(res);
              });
            }
            angular.element('#Modal').modal('show');
            $scope.reset_validation();
          }

        $scope.insertData = function(){
            angular.element('.loading').removeClass('hidden');
            var formdata = $scope.denda;
            $http.post(URL_LINK+'/insert', formdata)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                $scope.getData();
                angular.element('#Modal').modal('hide');
                
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
          var formdata = $scope.denda;
          var id = $scope.denda_id;
          $http.put(URL_LINK+'/update/'+id, formdata)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                $scope.getData();
                angular.element('#Modal').modal('hide');
                
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
                $scope.getData();
                angular.element('#ModalDelete').modal('hide');
                
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
          $scope.denda = {};
          $scope.denda_id = '';
        }




});
