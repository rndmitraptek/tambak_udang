<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("item.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'nama_item', title: 'Nama Item' },
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
                    url = "{{ route('item.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Item berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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

    $scope.input = {};
    $scope.edit = false;
    $scope.tambah = function(){
        $scope.input = {};
        $('#m_create').modal('show');
        $scope.edit = false;
    }

    $("#formInput").validate({
        rules: {
            nama_item: {
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
                ? "{{ route('item.update', ':uuid') }}"
                : "{{ route('item.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Item berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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