<script>
app.controller("myCtrl", function($scope,$http) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.pembelian-pakan.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_pembelian', title: 'No Pembelian Pakan' },
                { data: 'tanggal_pembelian', title: 'Tanggal Pembelian' },
                { data: 'supplier', title: 'Supplier' },
                { data: 'lokasi', title: 'Lokasi' },
                { data: 'siklus', title: 'Siklus' },
                { data: 'keterangan', title: 'keterangan' },
                { data: 'actions', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            url = "{{ route('finance.pembelian-pakan.get_detail',':uuid') }}"
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
                    url = "{{ route('finance.pembelian-pakan.delete',':uuid') }}";
                    url = url.replace(':uuid', x.uuid);
                    $http.delete(url)
                    .then(function(res){
                        if(res.data.success){
                            swal({
                                title: "Terhapus ",text: "Data Penaburan Benur berhasil dihapus!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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

    // $('.select2').select2({
    //     allowClear: true,
    //     width: '100%',
    //     minimumResultsForSearch: 0
    // });

    $scope.data_petak =[];
    $scope.detail = [];

    $scope.supplierColumns = [
        { data: 'kode_supplier', title: 'Kode Supplier' },
        { data: 'nama_supplier', title: 'Supplier' },
        { data: 'alamat_supplier', title: 'Alamat' },
        { data: 'telepon_supplier', title: 'Telf'},
        { data: 'email_supplier', title: 'Email'},
        { data: 'nama_perusahaan', title: 'Perusahaan'},
    ];
    $scope.selectSupplier = function(x){
        console.log(x);
        // $scope.input.nama_supplier = x.nama_supplier;
        $('#nama_supplier').val(x.nama_supplier);
        $scope.input.uuid_supplier = x.uuid;
    }

    $scope.id_lokasi = null;

    $scope.lokasi = [];
    $http.get("{{ route('finance.pembelian-pakan.lokasi') }}")
    .then(function(res){
        if(res.data.success){
            $scope.lokasi = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.siklus = [];
    $scope.get_siklus = function(){
        swal({title: "Processing...!",text: "Please Wait Load Data Siklus",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('finance.pembelian-pakan.siklus',':uuid_lokasi') }}";
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

    $scope.pakan = [];
    $http.get("{{ route('finance.pembelian-pakan.pakan') }}")
    .then(function(res){
        if(res.data.success){
            $scope.pakan = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.harga = 0;
    $scope.jumlah = 1;
    $scope.subtotal = 0;

    $scope.hitungSubtotal = function() {
        let harga = parseFloat($scope.harga) || 0;
        let jumlah = parseInt($scope.jumlah) || 0;
        $scope.subtotal = harga * jumlah;
    };

    $scope.daftarPakan = [];
    $scope.simpanPakan = function() {
        // cari data pakan dari list pakan
        let selected = $scope.pakan.find(p => p.id_pakan == $scope.input.id_pakan);

        if (!selected) {
            alert("Pilih pakan dulu");
            return;
        }

        let data = {
            id_pakan: selected.id_pakan,
            kode_pakan: selected.kode_pakan,
            nama_pakan: selected.nama_pakan,
            harga: parseFloat($scope.harga) || 0,
            jumlah: parseInt($scope.jumlah) || 0,
            subtotal: parseFloat($scope.subtotal) || 0
        };

        $scope.daftarPakan.push(data);

        $scope.getTotal = function() {
            return $scope.daftarPakan.reduce(function(total, item) {
                $scope.grand_total=total + (parseFloat(item.subtotal) || 0);
                return $scope.grand_total;
            }, 0);
        };

        // reset form
        $scope.input.id_pakan = '';
        $scope.harga = 0;
        $scope.jumlah = 1;
        $scope.subtotal = 0;

        // tutup modal
        $('#m_pakan').modal('hide');
    };

    $scope.hapusPakan = function(index) {
        $scope.daftarPakan.splice(index, 1);
    };

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
    $scope.cari_supplier = function(){
        $('#lookup_supplier').modal('show');
    }
    $scope.cari_lokasi = function(){
        $('#lookup_lokasi').modal('show');
    }
    $scope.cari_siklus = function(){
        $('#lookup_siklus').modal('show');
    }
    $scope.add_petak = function(){
        $('#m_petak').modal('show');
    }
    $scope.add_pakan = function(){
        $('#m_pakan').modal('show');
    }
    

    $("#formInput").validate({
        rules: {
            no_pembelian: { required: true },
            tanggal_pembelian: { required: true },
            uuid_lokasi: { required: true },
            uuid_siklus: { required: true }
        },
        invalidHandler: function(e, validator) {
            $('html, body').animate({
                scrollTop: $("#formInput").offset().top - 100
            }, 500);
        },
        submitHandler: function(form) {
            // Form valid, jalankan save
            $scope.simpanTransaksi();
        }
    });

    // Fungsi simpanTransaksi di AngularJS
    $scope.simpanTransaksi = function() {
        if ($scope.daftarPakan.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum ada pakan!',
                text: 'Tambahkan minimal 1 item pakan.'
            });
            return;
        }

        let dataPost = {
            header: {...$scope.input, tanggal_pembelian:$('#tanggal_pembelian').val(), jumlah_item:$scope.daftarPakan.length, total:$scope.grand_total},
            detail: $scope.daftarPakan
        };
        console.log('dataPost==>>',dataPost);

        // Loading
        swal({title: "Processing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })

        $http.post("{{ route('finance.pembelian-pakan.insert') }}", dataPost)
            .then(function(res) {
                Swal.close();
                if (res.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Transaksi berhasil disimpan.'
                    });
                    // Reset form
                    $scope.input = {};
                    $scope.daftarPakan = [];
                    $('#viewtabel').DataTable().ajax.reload(null, false);
                    $scope.form = 'list';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.data.message || 'Terjadi kesalahan'
                    });
                }
            })
            .catch(function(err) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: err.statusText || 'Terjadi kesalahan'
                });
            });
    };
});
</script>