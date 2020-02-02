myApp.factory('URL_LINK',function(BASE_URL){
  return BASE_URL+'/home/peminjaman';
});

myApp.controller('PeminjamanFormController', function($scope,$compile, $rootScope, $http, URL_LINK, $uibModal, DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$window){
          
        $http.get(URL_LINK+'/get_kode_peminjaman')
          .then(function success(e){ 
            $scope.kode_pinjam = e.data;
          }, function error(res) {
              console.log(res);
        });

        $scope.showModal = function(state){
            var param = new Array();
                param['button'] = state;

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

        $scope.insert_data = function(){
          var formData = new FormData($('#formulir')[0]);
          formData.append('tanggal_pinjam',angular.element('#tanggal_pinjam').val());
          formData.append('tanggal_kembali',angular.element('#tanggal_kembali').val());
          var config = {
              headers : {
                    'Content-Type': undefined
              }
          }
          $http.post(URL_LINK+'/insert', formData,config)
            .then(function success(e) {             
                $scope.alert_message = e.data.message;
                $scope.ShowAlert(e.data.status);
                $scope.reset_form();
                scrollTop();
              }, function error(res) {
                if(res.status == 422){
                  angular.element('#div_valid').show();
                  angular.element('#ul_valid').empty();
                  if(res.data.status){
                    angular.element('#ul_valid').append('<li>'+res.data.message+'</li>')
                  }else{
                    for(er in res.data.errors){
                      angular.element('#ul_valid').append('<li>'+res.data.errors[er][0]+'</li>')
                    }
                  }
                  scrollValidation();
                }
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
          angular.element('#div_valid').hide();
          angular.element('#ul_valid').empty();
        }

        function scrollTop(){
          var position = $('body').offset().top;
          document.documentElement.scrollTop = position;
        }

        function scrollValidation(){
          var position = $('#position_alert').offset().top;
          document.documentElement.scrollTop = position;
        }

        $scope.reset_form = function(){
          angular.element('#tanggal_pinjam').val('');
          angular.element('#tanggal_kembali').val('');
          $rootScope.$emit('clear_date',{});
          $scope.reset_anggota();
          $scope.buku_id = [];
          $scope.qty = [];
          $('.tr_append').remove();
          $('#tr_no_data').show();
          $scope.reset_validation();
        }

        $rootScope.$on('set_anggota',function($even,data){
          set_anggota(data);
        });

        $rootScope.$on('set_buku',function($even,data){
          set_buku(data);
        });

        function set_anggota(data){
          $scope.anggota = {};
          angular.element('#nomor_anggota').show();
          angular.element('#pilih_peminjam').hide();
          $scope.anggota.id = data.id;
          $scope.anggota.nomor = data.nomor_anggota;
          $scope.anggota.nama = data.nama;
          $scope.anggota.alamat = data.alamat;
          $scope.anggota.no_telepon = data.no_telepon;
        }

        $scope.reset_anggota = function(){
          $scope.anggota = {};
          angular.element('#nomor_anggota').hide();
          angular.element('#pilih_peminjam').show();
        }

         function set_buku(data){
            angular.element('#tr_no_data').hide();
            var table = $compile(data.table_row)($scope);
            var id   = data.buku.id;
            if($scope.buku_id == undefined){
              $scope.buku_id = [];
              $scope.qty = [];
            }

            if($scope.buku_id[id] != id){
              $('#tb_buku').append(table);
              // set value ng-model
              $scope.buku_id[id] = id;
            }else{
              alert('Gagal! Anda Sudah Menambahkan Data Yang Dipilih.');
            }
         }

      $scope.RemoveTr = function(id){
        var count = $('#tb_buku tr').length;
        if(count===2){
          $('#tr_no_data').show();

        }
        $scope.buku_id[id]   = '';
        $scope.qty[id]   = '';
       $('#remove_tr'+id).closest('tr').remove();
      }

          

});

myApp.controller('ModalCtrl', function ($uibModalInstance,param,$rootScope,$scope,$http,URL_LINK,$log,$compile, DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder) {           
        
    if(param['button'] == 'anggota')
    {
      $scope.modal_title = 'Pilih Anggota';
      var link_url = URL_LINK+'/table_anggota';
      var column = [
                DTColumnBuilder.newColumn('DT_RowIndex').withTitle('No'),
                DTColumnBuilder.newColumn('nomor_anggota').withTitle('Nomor Anggota'),
                DTColumnBuilder.newColumn('nama').withTitle('Nama'),
                DTColumnBuilder.newColumn('alamat').withTitle('Alamat'),
                DTColumnBuilder.newColumn('action').withTitle('Opsi').withClass('text-center').notSortable(),
      ];
    }
    else if(param['button'] == 'buku')
    {
      $scope.modal_title = 'Pilih Buku';
      var link_url = URL_LINK+'/table_buku';
      var column = [
                DTColumnBuilder.newColumn('DT_RowIndex').withTitle('No'),
                DTColumnBuilder.newColumn('kode').withTitle('Kode Buku'),
                DTColumnBuilder.newColumn('judul').withTitle('Judul'),
                DTColumnBuilder.newColumn('stok.stok_qty').withTitle('Stok Buku'),
                DTColumnBuilder.newColumn('action').withTitle('Opsi').withClass('text-center').notSortable(),
      ];
    } 

    // start datatable
    $scope.vm = {};
    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('ajax', {
      url: link_url,
      type: 'GET',

    })
      .withDataProp('data')
      .withOption('processing', true)
      .withOption('serverSide', true)
      .withPaginationType('full_numbers')
      .withOption('lengthMenu', [
            [5,10],
            [5,10]
      ])
      .withDisplayLength(5)
              //this is compile ng-click
      .withOption('createdRow', function(row) {
        // Recompiling so we can bind Angular directive to the DT
        $compile(angular.element(row).contents())($scope);
      })

      $scope.vm.dtColumns = column;
      // end datatable


    $scope.getAnggota = function(id){
      $http.get(URL_LINK+'/get_anggota/'+id)
        .then(function success(e){  
            $rootScope.$emit('set_anggota',e.data);
            $scope.ok();
        }, function error(res) {
            console.log(res);
      });
    } 

    $scope.getBuku = function(id){
      $http.get(URL_LINK+'/get_buku/'+id)
        .then(function success(e){  
            $rootScope.$emit('set_buku',e.data);
            $scope.ok();
        }, function error(res) {
            console.log(res);
      });
    }

    $scope.ok = function () {
      $uibModalInstance.close();
    };

    $scope.cancel = function () {
      $uibModalInstance.dismiss('cancel');
    };


});

myApp.controller('DatePickerCtrl',function ($scope,$rootScope) { 
  $rootScope.$on('clear_date',function(){
      $scope.clear_start_date();
      $scope.clear_end_date();
  });
  // date picker
  // set start date
  $scope.today_start_date = function() {
    $scope.input_start_date = new Date();
  };

  $scope.clear_start_date = function() {
    $scope.input_start_date = null;
  };

  $scope.start_date = function() {
    $scope.popup1.opened = true;
  };

  // set end date
  $scope.today_end_date = function() {
    $scope.input_end_date = new Date();
  };

  $scope.clear_end_date = function() {
    $scope.input_end_date = null;
  };

  $scope.end_date = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.input_start_date = new Date(year, month, day);
    $scope.input_end_date = new Date(year, month, day);
  };

  $scope.popup2 = {
    opened: false
  };

  $scope.popup1 = {
    opened: false
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    // return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };

  $scope.toggleMin();

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[1];
  $scope.altInputFormats = ['M!/d!/yyyy']; 

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }
});   
