myApp.controller('PeminjamanDataController', function($scope,$compile,$rootScope, $http, BASE_URL,DTOptionsBuilder, DTColumnBuilder, DTColumnDefBuilder,$log){

          var URL_LINK = BASE_URL+'/home/peminjaman';
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
              // DTColumnBuilder.newColumn('DT_RowIndex').withTitle('No'),
              DTColumnBuilder.newColumn('kode').withTitle('Kode'),
              DTColumnBuilder.newColumn('anggota.nama').withTitle('Nama Peminjam'),
              DTColumnBuilder.newColumn('tgl_pinjam').withTitle('Tanggal Pinjam'),
              DTColumnBuilder.newColumn('tgl_kembali').withTitle('Tanggal Kembali'),
              DTColumnBuilder.newColumn('status_txt').withTitle('Status'),
              DTColumnBuilder.newColumn('proses').withTitle('Proses').withClass('text-center').notSortable(),
              DTColumnBuilder.newColumn('aksi').withTitle('Opsi').withClass('text-center').notSortable(),
          ];

          $scope.vm.dtInstance = {};
          $scope.vm.reloadData = reloadData;

          function reloadData () {
              $scope.vm.dtInstance.reloadData();
          };

          // end of datatable


          $scope.showModal = function(state,id){
            getData(id);
            getDenda();
            $scope.kode = id;
            $scope.reset_form();
            $scope.modal_title = 'Proses Pengembalian Buku ' +id;
            angular.element('#Modal').modal('show');
            $scope.reset_validation();
          }

          function getDenda(){
            $http.get(URL_LINK+'/get_denda')
              .then(function success(e){  
                  $scope.data_denda = e.data.nominal;
              }, function error(res) {
                  console.log(res);
            });
          };

          function getData(kode){
            $http.get(URL_LINK+'/get_pinjam/'+kode)
              .then(function success(e){  
                  $scope.data_pinjam = e.data.pinjam;
              }, function error(res) {
                  console.log(res);
            });
          }

          // var formatDate = function(date){
          //     // return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + (date.getDate() ) ;
          //     new Date(course_time.date.getMonth());
          // }

          $scope.on_change_date = function(state){
            var date;
            var tgl_kembali = new Date($scope.data_pinjam.tgl_kembali);
            switch(state){
             case 'date_change':
                date = new Date(angular.element('#tanggal_dikembalikan').val());
                // timeDiff = Math.abs(tgl_kembali.getTime() - date.getTime());
                date = Math.ceil(parseInt((date - tgl_kembali) / (24*3600*1000)));
              break;
            case 'today':
                date = new Date();
                // timeDiff = Math.abs(tgl_kembali.getTime() - date.getTime());
                date = Math.ceil(parseInt((date - tgl_kembali) / (24*3600*1000)));
              break;
            case 'clear':
                date = null;
              break;
            
            }
            
            if(date != null){
              $scope.jum_terlambat = date;
              $scope.on_change_jumlah();
            }else{
              $scope.jum_terlambat = 0;
            }
          }

          $scope.on_change_jumlah = function(){
            var denda = ($scope.data_denda != undefined)?$scope.data_denda:0;
            $scope.denda_per_hari = denda;
            $scope.denda = parseInt($scope.jum_terlambat * denda);
          }

          

        $scope.UpdateData = function(){
            angular.element('.loading').removeClass('hidden');
            var kode = $scope.kode;
            $http.put(URL_LINK+'/update/'+kode,{
              'tanggal_kembali' : angular.element('#tanggal_dikembalikan').val(),
              'denda'           : $scope.denda,
            })
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
          $rootScope.$emit('clear_date',{})
          $scope.jum_terlambat = 0;
          $scope.denda_per_hari = '';
          $scope.denda = '';
        }

});

myApp.controller('DatePickerCtrl',function ($scope,$rootScope) { 
  $rootScope.$on('clear_date',function(){
      $scope.clear_tanggal_kembali();
  });
  // date picker
  // set start date
  $scope.today_tanggal_kembali = function() {
    $scope.input_tanggal_kembali = new Date();
  };

  $scope.clear_tanggal_kembali = function() {
    $scope.input_tanggal_kembali = null;
  };

  $scope.tanggal_kembali = function() {
    $scope.popup1.opened = true;
  };


  $scope.setDate = function(year, month, day) {
    $scope.input_tanggal_kembali = new Date(year, month, day);
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
