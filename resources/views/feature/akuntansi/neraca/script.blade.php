<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        
    });
    $scope.neraca = [];
    $scope.get_neraca = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.post("{{ route('akuntansi.jurnal.get_neraca') }}",{
            tanggal   : $('#tanggal').val(),
        })
        .then(function(res){
            if(res.data.success){
                $scope.neraca = res.data.data;
                $scope.tanggal = $('#tanggal').val();
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
});
</script>