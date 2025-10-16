<script>
app.controller("myCtrl", function($scope,$http) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.retur_pakan.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_retur', title: 'No Retur' },
                { data: 'tanggal_retur', title: 'Tanggal Retur' },
                { data: 'no_pembelian', title: 'No Pembelian' },
                { data: 'nama_lokasi', title: 'Lokasi' },
                { data: 'nama_siklus', title: 'Siklus' },
                { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'keterangan', title: 'Keterangan' },
                { 
                    data: 'status', 
                    title: 'Status',
                    render: function(data, type, row) {
                        return '<span class="badge badge-danger">'+data+'</span>';
                    }
                },
                { data: 'action', title: 'Action', orderable: false, searchable: false,width:'80px' },
            ]
        })

    });



    $scope.data_pakan =[];
    $scope.detail = [];

    $scope.pembelianColumns = [
        { data: 'no_pembelian', title: 'No Pembelian' },
        { data: 'tanggal_pembelian', title: 'Tanggal Pembelian' },
        { data: 'nama_supplier', title: 'Supplier' },
        { data: 'nama_lokasi', title: 'Lokasi' },
        { data: 'nama_siklus', title: 'Siklus'},
        { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
    ];
    
    $scope.selectPembelian = function(x){
        console.log(x);
        $scope.detail=[];
        $scope.input.no_pembelian = x.no_pembelian;
        $scope.input.uuid_pembelian = x.uuid;
        $scope.input.nama_supplier = x.nama_supplier;
        $scope.input.uuid_supplier = x.uuid_supplier;
        $scope.input.nama_lokasi = x.nama_lokasi;
        $scope.input.uuid_lokasi = x.uuid_lokasi;
        $scope.input.nama_siklus = x.nama_siklus;
        $scope.input.uuid_siklus = x.uuid_siklus;
        url = "{{ route('finance.retur_pakan.get_pembelian_detail',':uuid') }}"
        url = url.replace(':uuid', x.uuid)
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                let dataList = res.data.data || [];
                // mapping ulang untuk hitung subtotal
                $scope.data_pakan = dataList.map(item => {
                    const jumlah = parseFloat(item.jumlah) || 0;
                    const harga = parseFloat(item.harga) || 0;
                    item.subtotal = jumlah * harga;

                    return {
                        ...item
                    };
                });
            }
            Swal.close();
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
        $scope.$apply();
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
    $scope.cari_pembelian = function(){
        $('#lookup_pembelian').modal('show');
    }
    $scope.add_pakan = function(){
        $('#m_pakan').modal('show');
    }
    $scope.add_detail = function() {
        console.log($scope.data_pakan);
        $scope.data_pakan.forEach(function(item, index) {
            if (item.checked) {
                // Cek apakah uuid_pakan sudah ada di $scope.detail
                var sudahAda = $scope.detail.some(function(detailItem) {
                    return detailItem.uuid_pakan === item.uuid_pakan;
                });

                if (sudahAda) {
                    // Jika sudah ada, tandai tapi jangan tambah lagi
                    $scope.data_pakan[index].is_add = false;
                    toastr.warning('Pakan "' + item.nama_pakan + '" sudah ada di detail!');
                    return; // skip push
                }

                // Jika belum ada, baru tambahkan ke detail
                $scope.data_pakan[index].is_add = true;
                $scope.detail.push({
                    kode_pakan : item.kode_pakan,
                    nama_pakan : item.nama_pakan,
                    uuid_pakan : item.uuid_pakan,
                    jumlah_retur     : 0,
                    harga_per_kg      : item.harga,
                    subtotal   : 0,
                });
            } else {
                $scope.data_pakan[index].is_add = false;
            }
        });

        $('#m_pakan').modal('hide');
    };

    $scope.total_harga = 0;
    $scope.total_jumlah = 0;
    $scope.hitung=function(item){
        $scope.total_harga = 0;
        $scope.total_jumlah = 0;
        $scope.detail.forEach(function(detail, index) {
            detail.subtotal = parseInt(detail.harga_per_kg) * parseInt(detail.jumlah_retur);
            $scope.total_harga = $scope.total_harga + detail.subtotal;
            $scope.total_jumlah = $scope.total_jumlah + parseInt(detail.jumlah_retur);
        });
    }

    $scope.simpan = function(){
        $("#formInput").submit();
    }

    $("#formInput").validate({
        rules: {
            no_retur: {
                required: true,
                digits: true
            },
            tanggal_retur: {
                required: true
            },
            no_pembelian: {
                required: true
            }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formInput", -200)
        },
        submitHandler: function(e) {
            swal({title: "Processing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            let url = ($scope.edit)
                ? "{{ route('finance.retur_pakan.update', ':uuid') }}"
                : "{{ route('finance.retur_pakan.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal_retur      = $('#tanggal_retur').val();
            $scope.input.detail                 = $scope.detail;
            $scope.input.total           = $scope.total_harga

            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data transaksi berhasil tersimpan!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $scope.edit = true;
                        $scope.form = "list";
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


    $('#viewtabel').on('click', '.btn-batal', function() {
        var uuid = $(this).data('uuid');
        $scope.batalTransaksi(uuid);
    });

    $scope.batalTransaksi = function(uuid) {
        Swal.fire({
            title: 'Yakin ingin membatalkan transaksi?',
            text: "Stok pakan dan Trans Biaya akan dikembalikan sesuai transaksi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, batal!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                swal({title: "Processing...!",text: "Please Wait",
                    onOpen: function() {
                        swal.showLoading()
                    }
                })

                $http.get(`/finance/retur_pakan/batal/${uuid}`)
                    .then(function(res) {
                        if(res.data.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Transaksi berhasil dibatalkan!'
                            });
                            // reload datatable
                            $('#viewtabel').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.data.message
                            });
                        }
                    })
                    .catch(function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.data?.message || err.statusText
                        });
                    });
            }
        });
    }


    // Event handler DataTables
    $(document).on('click', '.btn-detail', function() {
        let uuid = $(this).data('uuid');

        $http.get('/finance/retur_pakan/get_detail/' + uuid).then(function(res){
            if(res.data.success){
                console.log(res.data)
                let transaksi = res.data.data;
                let html = '<table class="table table-bordered">';
                html += '<tr><th>Kode Pakan</th><th>Nama Pakan</th><th>Harga Per Kg</th><th>Jumlah (Kg)</th><th>Subtotal</th></tr>';

                transaksi.forEach(function(item){
                    html += `<tr>
                        <td>${item.kode_pakan}</td>
                        <td>${item.nama_pakan}</td>
                        <td class="text-right">${Number(item.harga_per_kg).toLocaleString()}</td>
                        <td class="text-right">${Number(item.jumlah_retur).toLocaleString()}</td>
                        <td class="text-right">${Number(item.subtotal).toLocaleString()}</td>
                    </tr>`;
                });

                html += '</table>';

                Swal.fire({
                    title: 'Detail Transaksi ' + transaksi[0].no_retur,
                    html: html,
                    width: '700px'
                });
            }
        }).catch(function(err){
            Swal.fire('Error', 'Gagal mengambil detail transaksi', 'error');
        });
    });
});
</script>