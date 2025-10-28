<script>
app.controller("myCtrl", function($scope,$http,API) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));

        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.po.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_po', title: 'No PO' },
                { data: 'tanggal_po', title: 'Tanggal PO' },
                { data: 'tanggal_kirim', title: 'Tanggal Kirim' },
                { data: 'nama_supplier', title: 'Nama Supplier' },
                { data: 'nama_lokasi', title: 'Nama Lokasi' },
                { data: 'nama_siklus', title: 'Nama Siklus' },
                { data: 'qty', title: 'Qty' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'harga_satuan', title: 'Harga Satuan' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'keterangan', title: 'keterangan' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.input.nama_supplier = x.supplier;
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
                    url = "{{ route('finance.po.delete',':uuid') }}";
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
        
        $scope.$apply();
    };

    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
        $scope.edit = false;
        //nomor
        $http.get("{{ route('long','po_benur') }}")
        .then(function(res){
            $scope.input.no_po = res.data.nomor;
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
        $scope.input = {
            qty:0,
            harga_satuan:0,
            total:0
        }
    }
    $scope.hitung = function(){
        console.log($scope.input.qty);
         console.log($scope.input.harga_satuan);
        $scope.input.total = parseFloat($scope.input.qty) * parseFloat($scope.input.harga_satuan)
        console.log($scope.input.total);
    }
    $scope.kembali = function(){
        $scope.form = "list";
        table.draw();
    }
    $scope.cari_supplier = function(){
        $('#lookup_supplier').modal('show');
    }

    $scope.lokasi = [];
    $http.get("{{ route('finance.po.lokasi') }}")
    .then(function(res){
        if(res.data.success){
            $scope.lokasi = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.siklus = [];
    $scope.get_siklus = function(){
        swal({title: "Presesing...!",text: "Please Wait Load Data Siklus",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('finance.po.siklus',':uuid_lokasi') }}";
        url = url.replace(':uuid_lokasi', $scope.input.uuid_lokasi);
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.siklus = res.data.data;
                Swal.close();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }
    
    $scope.input = {};
    $scope.edit = false;


    $("#formInput").validate({
        rules: {
            no_po: {
                required: true,
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
                ? "{{ route('finance.po.update', ':uuid') }}"
                : "{{ route('finance.po.insert') }}";

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