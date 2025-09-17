<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("panen.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_panen', title: 'No Panen' },
                { data: 'tanggal_panen', title: 'Tanggal Panen' },
                { data: 'siklus', title: 'Siklus' },
                { data: 'supplier', title: 'Supplier' },
                { data: 'lokasi', title: 'Lokasi' },
                { data: 'blok', title: 'Blok' },
                { data: 'petak', title: 'Petak' },
                { data: 'jenis_panen', title: 'Jenis Panen' },
                { data: 'keterangan', title: 'Keterangan' },
                { data: 'jumlah', title: 'Jumlah' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.input.nama_supplier = x.supplier;
            url = "{{ route('panen.get_detail',':uuid') }}"
            url = url.replace(':uuid', x.uuid)
            swal({title: "Presesing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            $http.get(url)
            .then(function(res){
                if(res.data.success){
                    $scope.detail = res.data.data;
                    $scope.hitung();
                }
                Swal.close();
            }).catch(function(error) {
                swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
            });
            $scope.edit = true;
            $scope.form = "input";
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
                    url = "{{ route('panen.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Penaburan Benur berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                            }).then(function(){
                                $('#m_create').modal('hide');
                                table.draw();
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
    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
    }
    $scope.kembali = function(){
        $scope.form = "list";
    }
    $scope.cari_supplier = function(){
        $('#m_supplier').modal('show');
    }
});
</script>