<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        $scope.vh70 = window.innerHeight * 0.7;
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("auth.role.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'role', title: 'role' },
                { data: 'keterangan', title: 'keterangan' },
                { data: 'created_at', title: 'created_at' },
                { data: 'updated_at', title: 'updated_at' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'260px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $('#m_create').modal('show');
            $scope.edit = true;
            $scope.$apply();
        });

        $('#viewtabel tbody').on('click', '#hapus', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            console.log(x);
            swal({
                title: "Apakah Kamu Yakin?",
                text: "menghapus data ini!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, Hapus!",
                cancelButtonText: "No, Batal!",
                reverseButtons: !0
            }).then(function(e) {
                if(e.value){
                    swal({title: "Presesing...!",text: "Please Wait",
                        onOpen: function() {
                            swal.showLoading()
                        }
                    })
                    url = "{{ route('auth.role.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Role berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                            }).then(function(){
                                $('#m_create').modal('hide');
                                table.ajax.reload(null, false);
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
                }
            })
        });

        $('#viewtabel tbody').on('click', '#member', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.id_role = x.id_role;
            $('#m_member').modal('show');
            $scope.reload_member_lov();
            $scope.reload_member_role();
        });

        $('#viewtabel tbody').on('click', '#akses', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.id_role = x.id_role;
            $('#m_akses').modal('show');
            $scope.reload_menu();
        });
    });

    $scope.menu = [];
    $scope.reload_menu = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('auth.role.get_menu_role',':id') }}";
        url = url.replace(':id', $scope.id_role);
        $http.get(url).then(function(res){
            $scope.menu = res.data.data;
            swal.close();
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }


    $scope.member = [];
    $scope.reload_member_lov = function(){
        url = "{{ route('auth.role.get_user_role',':id') }}";
        url = url.replace(':id', $scope.id_role);
        $http.get(url).then(function(res){
            $scope.member = res.data.data;
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.member_role = [];
    $scope.reload_member_role = function(){
        url = "{{ route('auth.role.get_user_role_active',':id') }}";
        url = url.replace(':id', $scope.id_role);
        $http.get(url).then(function(res){
            $scope.member_role = res.data.data;
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.member = [];
    $scope.input = {};
    $scope.edit = false;
    $scope.tambah = function(){
        $scope.input = {};
        $('#m_create').modal('show');
        $scope.edit = false;
    }

    $("#formInput").validate({
        rules: {
            role: {
                required: true
            }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formInput", -200)
        },
        submitHandler: function(e) {
            swal({title: "Presesing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            let url = ($scope.edit)
                ? "{{ route('auth.role.update', ':uuid') }}"
                : "{{ route('auth.role.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Role berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $('#m_create').modal('hide');
                        table.ajax.reload(null, false);
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

    $scope.tambah_member = function(){
        $http.post("{{ route('auth.role.insert_role') }}",{
            id_user : $scope.id_user,
            id_role : $scope.id_role
        })
        .then(function(res){
            if(res.data.success){
                swal({
                    title: "Tersimpan ",text: "Data Role berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                }).then(function(){
                    $scope.reload_member_lov();
                    $scope.reload_member_role();
                })
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

    $scope.hapus_member = function(x){
        console.log(x)
        swal({
            title: "Apakah Kamu Yakin?",
            text: "menghapus data ini!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, Hapus!",
            cancelButtonText: "No, Batal!",
            reverseButtons: !0
        }).then(function(e) {
            if(e.value){
                swal({title: "Presesing...!",text: "Please Wait",
                    onOpen: function() {
                        swal.showLoading()
                    }
                })
                url = "{{ route('auth.role.delete_user', ':id') }}";
                url = url.replace(':id', x.id_role_user);
                $http.delete(url)
                .then(function(res){
                    if(res.data.success){
                        swal({
                            title: "Terhapus ",text: "Data Role berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                        }).then(function(){
                            $scope.reload_member_lov();
                            $scope.reload_member_role();
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
            }
        })
    }

    $scope.update_menu = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.post("{{ route('auth.role.update_menu') }}",{
            data    : $scope.menu,
            id_role : $scope.id_role
        })
        .then(function(res){
            if(res.data.success){
                swal({
                    title: "Terhapus ",text: "Data Role berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                }).then(function(){
                    $scope.reload_member_lov();
                    $scope.reload_member_role();
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
    } 
});
</script>