myApp.controller('PetugasController', function($scope,$compile,$http, BASE_URL, DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){
          var URL_LINK = BASE_URL+'/home/master/petugas';
          var URL_IMAGE = BASE_URL+'/images';
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
              DTColumnBuilder.newColumn('nama').withTitle('Nama'),
              DTColumnBuilder.newColumn('nip').withTitle('Nip'),
              DTColumnBuilder.newColumn('email').withTitle('Email'),
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
              $scope.label_password = "Password:";
              // show btn insert
              $scope.btninsert = true;
              $scope.btnupdate = false;
              $scope.user.photo = URL_IMAGE+'/default-profile.png';
            }else{
              $scope.modal_title = "Edit Data";
              $scope.label_password = "Password: Baru";
              // show btn update
              $scope.btninsert = false;
              $scope.btnupdate = true;
              $scope.user = {};

              $http.get(URL_LINK+'/get_data/'+id)
              .then(function success(e){  
                  var data = e.data;
                  $scope.user_id  = data.id;
                  $scope.user.nip  = data.nip;
                  $scope.user.nama  = data.nama;
                  $scope.user.email = data.email;
                  $scope.user.photo = URL_IMAGE+data.photo;
                }, function error(res) {
                console.log(res);
              });

            }
            angular.element('#Modal').modal('show');
            $scope.reset_validation();
          }

        $scope.insertData = function(){
            angular.element('.loading').removeClass('hidden');
            var formData = new FormData($('#formulir')[0]);           
            var config = {
                headers : {
                    'Content-Type': undefined
                }
            }
            $http.post(URL_LINK+'/insert', formData, config).then(function success(e) {             
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
          var formData = new FormData($('#formulir')[0]);           
          var config = {
              headers : {
                    'Content-Type': undefined
              }
          }
          var id = $scope.user_id;
          $http.post(URL_LINK+'/update/'+id, formData,config)
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
          $scope.modal_delete_title = 'Hapus User '+nama;
          $scope.delete_message     = 'Anda akan menghapus user '+nama+'.';
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
          $scope.user = {};
          $('#src_photo').attr('src','');
          $('#photo').val('');
          $scope.user_id = '';
        }

        $scope.$on("fileProgress", function(e, progress) {
          $scope.progress = progress.loaded / progress.total;
        });
});



  myApp.directive("ngFileSelect", function(fileReader, $timeout) {
    return {
      scope: {
        ngModel: '='
      },
      link: function($scope, el) {
        function getFile(file) {
          fileReader.readAsDataUrl(file, $scope)
            .then(function(result) {
              $timeout(function() {
                $scope.ngModel = result;
              });
            });
        }

        el.bind("change", function(e) {
          var file = (e.srcElement || e.target).files[0];
          getFile(file);
        });
      }
    };
  });

myApp.factory("fileReader", function($q, $log) {
  var onLoad = function(reader, deferred, scope) {
    return function() {
      scope.$apply(function() {
        deferred.resolve(reader.result);
      });
    };
  };

  var onError = function(reader, deferred, scope) {
    return function() {
      scope.$apply(function() {
        deferred.reject(reader.result);
      });
    };
  };

  var onProgress = function(reader, scope) {
    return function(event) {
      scope.$broadcast("fileProgress", {
        total: event.total,
        loaded: event.loaded
      });
    };
  };

  var getReader = function(deferred, scope) {
    var reader = new FileReader();
    reader.onload = onLoad(reader, deferred, scope);
    reader.onerror = onError(reader, deferred, scope);
    reader.onprogress = onProgress(reader, scope);
    return reader;
  };

  var readAsDataURL = function(file, scope) {
    var deferred = $q.defer();

    var reader = getReader(deferred, scope);
    reader.readAsDataURL(file);

    return deferred.promise;
  };

  return {
    readAsDataUrl: readAsDataURL
  };
});

         
