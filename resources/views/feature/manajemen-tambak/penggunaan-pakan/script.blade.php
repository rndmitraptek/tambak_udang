<script>
app.controller("myCtrl", function($scope,$http) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.penggunaan_pakan.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            order: [[0, 'desc']],
            columns: [
                { data: 'id_penggunaan', visible: false },
                { data: 'no_penggunaan', title: 'No Penggunaan Pakan' },
                { data: 'tanggal_penggunaan', title: 'Tanggal Penggunaan' },
                { data: 'nama_lokasi', title: 'Lokasi' },
                { data: 'nama_siklus', title: 'Siklus' },
                { data: 'jumlah_petak', title: 'Jumlah Petak' },
                { 
                    data: 'total', 
                    name: 'total',
                    title: 'Total',
                    className: 'text-right',
                    render: function(data) {
                        return data ? 'Rp ' + parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
                { data: 'keterangan', title: 'keterangan' },
                { 
                    data: 'status', 
                    title: 'Status',
                    render: function(data, type, row) {
                        return '<span class="badge badge-danger">'+data+'</span>';
                    }
                },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

    });

    $scope.id_lokasi = null;

    $scope.lokasi = [];
    $http.get("{{ route('finance.penggunaan_pakan.lokasi') }}")
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
        url = "{{ route('finance.penggunaan_pakan.siklus',':uuid_lokasi') }}";
        url = url.replace(':uuid_lokasi', $scope.input.uuid_lokasi);
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.siklus = res.data.data;
                $scope.get_pakan();
                Swal.close();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }


    $scope.get_petak = function(){
        url = "{{ route('finance.penggunaan_pakan.get_petak',':uuid_siklus') }}";
        url = url.replace(':uuid_siklus', $scope.input.uuid_siklus);
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.petak = res.data.data;
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }


    $scope.pakan = [];
    
    $scope.get_pakan = function(){
        url = "{{ route('finance.penggunaan_pakan.pakan',':uuid_lokasi') }}";
        url = url.replace(':uuid_lokasi', $scope.input.uuid_lokasi);
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.pakan = res.data.data;
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.selected_petak = function() {
        let selected = $scope.petak.find(x => x.uuid === $scope.input.id_petak);
    };

    $scope.selected_pakan = function() {
        let selected = $scope.pakan.find(x => x.uuid === $scope.input.id_pakan);
        $scope.stok = selected ? (parseFloat(selected.stok) || 0) : 0;
        $scope.harga = selected ? (parseFloat(selected.harga) || 0) : 0;
    };

    $scope.harga = 0;
    $scope.jumlah = 1;
    $scope.subtotal = 0;

    $scope.hitungSubtotal = function() {
        let harga = parseFloat($scope.harga) || 0;
        let jumlah = parseInt($scope.jumlah) || 0;
        $scope.subtotal = harga * jumlah;
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
    $scope.cari_po = function(){
        $('#lookup_po').modal('show');
    }
    $scope.add_petak = function(){
        $('#m_petak').modal('show');
    }
    $scope.add_detail = function(){
        if (!$scope.input.id_pakan || !$scope.input.id_petak) {
            toastr.warning('Pakan dan Petak harus dipilih terlebih dahulu');
            return;
        }

        // ambil data dari pilihan dropdown
        let pakan = $scope.pakan.find(p => p.uuid == $scope.input.id_pakan);
        let petak = $scope.petak.find(p => p.uuid == $scope.input.id_petak);

        // validasi jumlah
        let jumlah = parseFloat($scope.jumlah || 0);
        if (jumlah <= 0) {
            toastr.warning('Jumlah penggunaan harus lebih dari 0');
            return;
        }

        // hitung subtotal
        let harga = parseFloat($scope.harga || 0);
        let subtotal = jumlah * harga;

        // cek apakah kombinasi id_pakan dan id_petak sudah ada di detail
        let existing = $scope.detail.find(item =>
            item.id_pakan == pakan.id_pakan && item.id_petak == petak.uuid
        );

        if (existing) {
            // update baris yang sama
            existing.jumlah = parseFloat(existing.jumlah) + jumlah;
            existing.subtotal = existing.jumlah * existing.harga;
        } else {
            // buat baris baru
            let newDetail = {
                id_pakan: pakan.id_pakan,
                kode_pakan: pakan.kode_pakan,
                nama_pakan: pakan.nama_pakan,
                id_petak: petak.id_petak,
                nama_blok: petak.blok,
                nama_petak: petak.petak,
                jumlah: jumlah,
                harga: harga,
                subtotal: subtotal
            };

            $scope.detail.push(newDetail);
        }

        // hitung ulang total
        $scope.hitungTotal();

        // reset input modal
        $scope.input.id_pakan = '';
        $scope.input.id_petak = '';
        $scope.jumlah = 0;
        $scope.harga = 0;
        $scope.subtotal = 0;
        $scope.stok = 0;
        
        $('#m_petak').modal('hide');
    }

    $scope.hitungTotal = function () {
        let total_jumlah = 0;
        let total_harga = 0;
        let grand_total = 0;

        $scope.detail.forEach(item => {
            total_jumlah += parseFloat(item.jumlah || 0);
            total_harga += parseFloat(item.harga || 0);
            grand_total += parseFloat(item.subtotal || 0);
        });

        $scope.total_jumlah = total_jumlah;
        $scope.total_harga = total_harga;
        $scope.grand_total = grand_total;
    };

    $scope.remove_detail = function (index) {
        $scope.detail.splice(index, 1);
        $scope.hitungTotal();
    };



    $scope.simpan = function(){
        $("#formInput").submit();
    }

    $("#formInput").validate({
        rules: {
            no_penggunaan: { required: true },
            tanggal_penggunaan: { required: true },
            waktu: { required: true },
            uuid_lokasi: { required: true },
            uuid_siklus: { required: true }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formInput", -200);
        },
        submitHandler: function(e) {
            // Validasi: pastikan ada detail
            if(!$scope.detail || $scope.detail.length === 0){
                swal({
                    title: "Gagal",
                    text: "Detail penggunaan pakan belum diisi.",
                    type: "warning",
                    confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                });
                return false;
            }

            // hitung total dari detail
            let total = 0;
            $scope.detail.forEach(d => {
                total += parseFloat(d.subtotal || 0);
            });

            swal({
                title: "Processing...!",
                text: "Please Wait",
                onOpen: function() { swal.showLoading() }
            });

            // tentukan URL insert / update
            let url = ($scope.edit)
                ? "{{ route('finance.penggunaan_pakan.update', ':uuid') }}"
                : "{{ route('finance.penggunaan_pakan.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }

            // kumpulkan data utama
            $scope.input.tanggal_penggunaan = $('#tanggal_penggunaan').val();
            $scope.input.waktu = $('#waktu').val();
            $scope.input.total = total;
            $scope.input.jumlah_petak = $scope.detail.length;
            $scope.input.detail = $scope.detail;

            // kirim ke backend
            $http.post(url, $scope.input)
            .then(function(res){
                if(res.data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Transaksi berhasil disimpan.'
                    });
                    $scope.kembali();
                }else{
                    swal({
                        title: "Gagal",
                        text: res.data.message || 'Terjadi kesalahan saat menyimpan data',
                        type: "warning",
                        confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    });
                }
            })
            .catch(function(error) {
                swal({
                    title: error.statusText || "Error",
                    text: error.data?.message || "Terjadi kesalahan pada server",
                    type: "error",
                    confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                });
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

                $http.get(`/finance/penggunaan_pakan/batal/${uuid}`)
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

        $http.get('/finance/penggunaan_pakan/get_detail/' + uuid).then(function(res){
            if(res.data.success){
                console.log(res.data)
                let transaksi = res.data.data;
                let html = '<table class="table table-bordered">';
                html += '<tr><th>Kode Pakan</th><th>Nama Pakan</th><th>Blok</th><th>Petak</th><th>Harga Per Kg</th><th>Jumlah (Kg)</th><th>Subtotal</th></tr>';

                transaksi.forEach(function(item){
                    html += `<tr>
                        <td>${item.kode_pakan}</td>
                        <td>${item.nama_pakan}</td>
                        <td>${item.nama_blok}</td>
                        <td>${item.nama_petak}</td>
                        <td class="text-right">${Number(item.harga_per_kg).toLocaleString()}</td>
                        <td class="text-right">${Number(item.jumlah).toLocaleString()}</td>
                        <td class="text-right">${Number(item.subtotal).toLocaleString()}</td>
                    </tr>`;
                });

                html += '</table>';

                Swal.fire({
                    title: 'Detail Transaksi ' + transaksi[0].no_penggunaan,
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