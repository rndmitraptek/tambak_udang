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
                { data: 'action', title: 'action', orderable: false, searchable: false },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $('#m_create').modal('show');
            $scope.$apply();
        });

        $('#viewtabel tbody').on('click', '#hapus', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            console.log(x);
        });

    });

    $scope.input = {};
    $scope.tambah = function(){
        $scope.input = {};
        $('#m_create').modal('show');
    }
    $("#formInput").validate({
        rules: {
            urut: {
                required: !0,
            },
            label: {
                required: !0
            },
            icon: {
                required: !0
            },
            route_link: {
                required: !0
            },
            parent: {
                required: !0
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
            $http.post("{{ route('auth.menu.insert') }}",$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Menu berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $('#m_create').modal('hide');
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
                // simpan log error proses backend
            });
            return false;
        }
    });
});
</script>