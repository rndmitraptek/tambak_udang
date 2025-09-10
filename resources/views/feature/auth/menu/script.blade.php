<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("auth.menu.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'urut', title: 'urut' },
                { data: 'label', title: 'label' },
                { data: 'icon', title: 'icon' },
                { data: 'route_link', title: 'route_link' },
                { data: 'is_parent', title: 'is_parent' },
                { data: 'id_menu_parent', title: 'id_menu_parent' },
                { data: 'created_by', title: 'created_by' },
                { data: 'created_at', title: 'created_at' },
                { data: 'updated_by', title: 'updated_by' },
                { data: 'updated_at', title: 'updated_at' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
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
                    url = "{{ route('auth.menu.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Menu berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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
    });
    $scope.menu_parent = [];
    $http.get("{{ route('auth.menu.menu_parent') }}")
    .then(function(res){
        if(res.data.success){
            $scope.menu_parent = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.input = {};
    $scope.edit = false;
    $scope.tambah = function(){
        $scope.input = {
            icon : 'flaticon-layers'
        };
        $('#m_create').modal('show');
        $scope.edit = false;
    }

    $("#formInput").validate({
        rules: {
            urut: {
                required: true,
                digits: true
            },
            label: {
                required: true
            },
            icon: {
                required: true
            },
            route_link: {
                required: true
            },
            parent: {
                required: true
            }
        },
        messages: {
            urut: {
                digits: "Kolom urut harus berupa angka saja"
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
                ? "{{ route('auth.menu.update', ':uuid') }}"
                : "{{ route('auth.menu.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Menu berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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
});
</script>