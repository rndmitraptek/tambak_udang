<script>
app.controller("myCtrl", function($scope,$http,API) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));

        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.pembayaran_hutang_supplier.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_faktur', title: 'No Faktur' },
                { data: 'tanggal_bayar', title: 'Tanggal Bayar' },
                { data: 'nama_supplier', title: 'Supplier' },
                { data: 'nama_supplier', title: 'Nama Supplier' },
                { data: 'total_hutang', title: 'Total Hutang' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total_piutang', title: 'Total Piutang' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total_bayar', title: 'Total Bayar' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'keterangan', title: 'keterangan' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            url = "{{ route('finance.pembayaran_hutang_supplier.get_detail',':uuid') }}"
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
                    $scope.edit = true;
                    $scope.form = "input";
                    $scope.$apply();
                }
                Swal.close();
            }).catch(function(error) {
                swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
            });
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
                    url = "{{ route('finance.pembayaran_hutang_supplier.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data PO berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                            }).then(function(){
                                
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
                }
            })
        });
        
    });

    $scope.input = {};
    $scope.input.hutang = [];
    $scope.input.piutang = [];
    $scope.input.transfer = [];
    $scope.input.tunai = [];
    $scope.input.giro = [];
    $scope.supplierColumns = [
        { data: 'kode_supplier', title: 'Kode Supplier' },
        { data: 'nama_supplier', title: 'Nama Supplier' },
        { data: 'nama_perusahaan', title: 'Perusahaan' },
        { data: 'telepon_supplier', title: 'Telepon' }
    ];
    $scope.id_lokasi = null;
    $scope.selectSupplier = function(row) {
        console.log(row);
        $scope.input.uuid_supplier = row.uuid;
        $scope.input.nama_supplier = row.nama_supplier;
        $scope.id_lokasi = row.id_lokasi;
        url = "{{ route('finance.pembayaran_hutang_supplier.get_hutang_piutang',':uuid') }}"
        url = url.replace(':uuid', row.uuid)
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.input.hutang = res.data.data.hutang;
                $scope.input.piutang = res.data.data.piutang;
                Swal.close();
                //$scope.$apply();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    };

    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
        $scope.edit = false;
        $scope.input = {}
    }

    $scope.kembali = function(){
        $scope.form = "list";
        table.draw();
    }
    $scope.cari_supplier = function(){
        $('#lookup_supplier').modal('show');
    }
    
    $scope.input = {};
    $scope.edit = false;


    $("#formInput").validate({
        rules: {
            no_po: {
                required: true,
                digits: true
            },
            nama_supplier: {
                required: true
            },
            uuid_lokasi: {
                required: true
            },
            tanggal_po: {
                required: true
            },
            qty: {
                required: true
            },
            harga_satuan: {
                required: true
            },
            total: {
                required: true
            }
        },
        messages: {
            nama_supplier: {
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
                ? "{{ route('finance.pembayaran_hutang_supplier.update', ':uuid') }}"
                : "{{ route('finance.pembayaran_hutang_supplier.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal_po     = $('#tanggal_po').val();
            $scope.input.tanggal_kirim  = $('#tanggal_kirim').val();
            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data PO berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        table.draw();
                        $scope.edit = true;
                        $scope.form = "input";
                        $scope.input.uuid = res.data.data.uuid;
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