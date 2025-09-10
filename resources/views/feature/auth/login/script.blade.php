<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        
    });

    $("#formLogin").validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formLogin", -200)
        },
        submitHandler: function(e) {
            swal({title: "Presesing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            $http.post("{{ route('auth.user.login') }}",{
                username : $scope.username,
                password : $scope.password
            }, { withCredentials: true })
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Login Berhasil ",text: "username dan password cocok!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        window.location.href = "{{ url('lokasi') }}";
                    })
                }else{
                    swal({
                        title: "Gagal ",text: res.data.message,type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $('#m_create').modal('hide');
                    })
                }
            }).catch(function(error) {
                swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
            });
            return false;
        }
    });
});
</script>