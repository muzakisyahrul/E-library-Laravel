myApp.controller('StokBukuController', function($scope,$compile, $http, BASE_URL,DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){

          var URL_LINK = BASE_URL+'/home/master/stok_buku';
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
              DTColumnBuilder.newColumn('buku.kode').withTitle('Kode Buku'),
              DTColumnBuilder.newColumn('buku.judul').withTitle('Judul Buku'),
              DTColumnBuilder.newColumn('stok_qty').withTitle('Jumlah Stok'),
              DTColumnBuilder.newColumn('action').withTitle('Opsi').withClass('text-center').notSortable(),
          ];

          $scope.vm.dtInstance = {};
          $scope.vm.reloadData = reloadData;

          function reloadData () {
              $scope.vm.dtInstance.reloadData();
          };

          // end of datatable

        $scope.getBuku = function(){
          $http.get(URL_LINK+'/get_buku')
            .then(function success(e){  
                var data = e.data;
                data.unshift({id:'',judul:'Pilih Buku'});
                $scope.daftar_buku = data;
            }, function error(res) {
                console.log(res);
          });
        }

        $scope.showModal = function(state,id,buku_id){
            $scope.getBuku();
            $scope.daftar_opsi_qty =[ {tipe:'',text:"Pilih Opsi Stok"},
                                      {tipe:1,text:"Penambahan Stok"},
                                      {tipe:2,text:"Pengurangan Stok"} ];
            $scope.reset_form();
            if(state == 'add'){
              $scope.modal_title = "Tambah Data";
              $scope.label_stok  = "Jumlah Stok:";
              // show btn insert
              $scope.btninsert = true;
              $scope.btnupdate = false;
              $scope.disabled_buku = false;
              $scope.disabled_qty  = false;
              $scope.edit_stok = false;
              $scope.stok.buku = '';
            }else{
              $scope.modal_title = "Edit Data";
              $scope.label_stok  = "Stok Saat Ini:";
              // show btn update
              $scope.btninsert = false;
              $scope.disabled_qty  = true;
              $scope.edit_stok = true;
              $scope.btnupdate = true;

              $http.get(URL_LINK+'/get_data/'+id)
              .then(function success(e){  
                  var data = e.data;
                  $scope.stok_id  = data.id;
                  $scope.stok.buku  = buku_id;
                  $scope.stok.qty = data.stok_qty;
                }, function error(res) {
                console.log(res);
              });
              $scope.disabled_buku = true;
              $scope.stok.opsi_qty = '';
            }
            angular.element('#Modal').modal('show');
            $scope.reset_validation();
        }

        $scope.show_hide_stok = function(){
          var tipe = $scope.stok.opsi_qty;
          console.log(tipe);
          if(tipe>0){
            if(tipe==1){
              $scope.label_new_stok = 'Tambah Stok:';
            }else{
              $scope.label_new_stok = 'Kurangi Stok:';
            }
            $scope.new_stok = true;
          }else{
            $scope.new_stok = false;
          }
        }

        $scope.insertData = function(){
            angular.element('.loading').removeClass('hidden');
            var formdata = $scope.stok;
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
          var formdata = $scope.stok;
          var id = $scope.stok_id;
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
          $scope.stok = {};
          $scope.stok_id = '';
          $scope.new_stok = false;
        }




});
