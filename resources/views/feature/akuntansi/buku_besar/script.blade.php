<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (selected) {
            let startDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', startDate);
        });

        // Inisialisasi End Date
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (selected) {
            let endDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', endDate);
        });
    });
    $scope.buku_besar = [];
    $scope.get_buku_besar = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.post("{{ route('akuntansi.jurnal.get_buku_besar') }}",{
            tanggal_mulai   : $('#startDate').val(),
            tanggal_selesai : $('#endDate').val(),
            kode_coa        : $scope.kode_coa,
            saldo_normal    : $scope.saldo_normal
        })
        .then(function(res){
            if(res.data.success){
                $scope.buku_besar = res.data.data;
                $scope.start_date = $('#startDate').val();
                $scope.end_date = $('#endDate').val();
                Swal.close();
            }else{
                swal({
                    title: "Gagal ",text: res.data.message,type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                }).then(function(){
                })
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }
    
    $scope.coaColumns = [
        { data: 'kode_coa', title: 'Kode COA' },
        { data: 'nama_coa', title: 'Nama COA' },
        { data: 'tipe_coa', title: 'tipe' },
        { data: 'pos_laporan', title: 'laporan' },
        { data: 'saldo_normal', title: 'Saldo Normal' }
    ];
    $scope.handleClickCoa = function(){
        $('#lookup_coa').modal('show');
    }
    $scope.kode_coa = '';
    $scope.nama_coa = '';
    $scope.saldo_normal = '';
    $scope.selectCoa = function(param){
        $scope.kode_coa     = param.kode_coa;
        $scope.nama_coa     = param.nama_coa;
        $scope.saldo_normal = param.saldo_normal;
        $scope.$apply();
    }
});
</script>