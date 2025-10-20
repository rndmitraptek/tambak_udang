<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.pembelian_barang.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_pembelian_barang', title: 'No Pembelian Barang' },
                { data: 'tanggal_pembelian_barang', title: 'Tanggal Pembelian' },
                { data: 'nama_lokasi', title: 'Lokasi' },
                { data: 'nama_supplier', title: 'Supplier' },
                { data: 'jumlah', title: 'Jumlah' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'pembayaran', title: 'Pembayaran' },
                { data: 'keterangan', title: 'Keterangan' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            url = "{{ route('finance.pembelian_barang.get_detail',':uuid') }}"
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
                    url = "{{ route('finance.pembelian_barang.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Pembelian Barang berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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

    

    $scope.lokasi = [];
    $http.get("{{ route('finance.pembelian_barang.get_lokasi') }}")
    .then(function(res){
        if(res.data.success){
            $scope.lokasi = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.cari_supplier = function(){
        $('#lookup_supplier').modal('show');
    }

    $scope.supplierColumns = [
        { data: 'kode_supplier', title: 'Kode supplier' },
        { data: 'nama_supplier', title: 'Nama supplier' },
        { data: 'telepon_supplier', title: 'Telepon' }
    ];

    $scope.selectSupplier = function(param){
        $scope.input.nama_supplier = param.nama_supplier;
        $scope.input.uuid_supplier = param.uuid;
    }

    $scope.handleClickBarang = function(){
        $('#lookup_barang').modal('show');
    }
    $scope.barangColumns = [
        { data: 'nama_barang', title: 'Nama Barang' },
        { data: 'harga', title: 'Harga' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
    ];
    $scope.detail=[]
    $scope.selectBarang = function(param){
        console.log($scope.item);
        console.log(param)
        $scope.detail.push({
            uuid_barang    : param.uuid,
            nama_barang    : param.nama_barang,
            harga          : param.harga,
            qty            : 1,
            subtotal       : param.harga
        });
        $scope.hitung();
        $scope.$apply();
    }

    $scope.jumlah = 0;
    $scope.total = 0;
    $scope.hitung = function(){
        $scope.jumlah = 0;
        $scope.total = 0;
        $scope.detail.forEach(function(detail, index) {
            detail.subtotal = parseInt(detail.qty) * parseInt(detail.harga);
            $scope.jumlah = $scope.jumlah + parseInt(detail.qty);
            $scope.total = $scope.total + parseInt(detail.subtotal);
        });
    }

    $scope.form = "list";
    $scope.tambah = function(){
        $scope.input = {};
        $scope.detail = []
        $scope.form = "input";
        $scope.edit = false;
    }
    $scope.kembali = function(){
        $scope.form = "list";
        table.draw();
    }
    $scope.simpan = function(){
        $("#formInput").submit();
    }
    $("#formInput").validate({
        rules: {
            no_pembelian_barang: {
                required: true
            },
            tanggal_pembelian_barang: {
                required: true
            },
            uuid_lokasi: {
                required: true
            },
            nama_supplier: {
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
                ? "{{ route('finance.pembelian_barang.update', ':uuid') }}"
                : "{{ route('finance.pembelian_barang.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal_pembelian_barang   = $('#tanggal_pembelian_barang').val();
            $scope.input.detail                     = $scope.detail;
            $scope.input.total                      = $scope.total
            $scope.input.jumlah                     = $scope.jumlah

            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Pembelian Barang berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $scope.edit = true;
                        $scope.form = "input";
                        $scope.input.uuid = res.data.data.uuid;
                        table.draw();
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
            return false;
        }
    });
});
</script>