<script>
app.controller("myCtrl", function($scope,$http,API) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        $("#waktu_transfer").inputmask("9999-99-99 99:99:99", {
            placeholder: "yyyy-mm-dd hh:mm:ss",
            autoUnmask: false,
            oncomplete: function() {
                var val = $(this).val();
                var scope = angular.element(this).scope();
                scope.$apply(function(){
                    scope.form_transfer.waktu_transfer = val;
                });
            }
        });
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.pembayaran_piutang_customer.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_faktur', title: 'No Faktur' },
                { data: 'tanggal_bayar', title: 'Tanggal Bayar' },
                { data: 'nama_customer', title: 'Customer' },
                { data: 'total_bayar', title: 'Total Bayar' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'keterangan', title: 'keterangan' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.get_detail(x.uuid);
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

    $scope.get_detail = function(uuid){
        url = "{{ route('finance.pembayaran_piutang_customer.get_detail',':uuid') }}"
        url = url.replace(':uuid', uuid)
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.detail = res.data.data;
                $scope.form = "detail";
                Swal.close();
                $scope.$apply();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.default_value = function(){
        $scope.input = {};
        $scope.edit = false;
        $scope.input.hutang = [];
        $scope.input.piutang = [];
        $scope.total_hutang = 0;
        $scope.total_piutang = 0;
        $scope.total_bayar = 0;
    }
    $scope.customerColumns = [
        { data: 'kode_customer', title: 'Kode Customer' },
        { data: 'nama_customer', title: 'Nama Customer' },
        { data: 'alamat_customer', title: 'Alamat' },
        { data: 'telepon_customer', title: 'Telepon' }
    ];
    $scope.rekeningColumns = [
        { data: 'nama_bank', title: 'Nama Bank' },
        { data: 'atas_nama', title: 'Atas Nama' },
        { data: 'no_rekening', title: 'No Rekening' }
    ]
    $scope.id_lokasi = null;
    $scope.selectCustomer = function(row) {
        console.log(row);
        $scope.input.uuid_customer = row.uuid;
        $scope.input.nama_customer = row.nama_customer;
        $scope.input.kode_customer = row.kode_customer;
        $scope.input.alamat_customer = row.alamat_customer;
        $scope.id_lokasi = row.id_lokasi;
        url = "{{ route('finance.pembayaran_piutang_customer.get_piutang',':uuid') }}"
        url = url.replace(':uuid', row.uuid)
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.input.piutang = res.data.data.piutang;
                Swal.close();
                //$scope.$apply();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    };

    $scope.selectRekening = function(row){
        $scope.form_transfer.uuid_rekeing = row.uuid;
        $scope.form_transfer.rekening = row.nama_bank;
        $scope.$apply();
    }

    $scope.selectRekeningGiro = function(row){

        $scope.form_giro.uuid_rekeing = row.uuid;
        $scope.form_giro.rekening = row.nama_bank;
        console.log($scope.form_giro);
        $scope.$apply();
    }

    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
        $scope.edit = false;
        $scope.default_value();
    }

    $scope.kembali = function(){
        $scope.form = "list";
        table.draw();
    }

    $scope.cari_customer = function(){
        $('#lookup_customer').modal('show');
    }
    $scope.cari_rekening = function(){
        $('#lookup_rekening').modal('show');
    }
    $scope.cari_rekening_giro = function(){
        $('#lookup_rekening_giro').modal('show');
    }

    $scope.hitung = function(){
        $scope.total_bayar = 0;
        $scope.input.piutang.forEach(function(piutang, index) {
            if(piutang.checked){
                console.log(piutang.bayar);
                $scope.total_bayar = $scope.total_bayar + parseFloat(piutang.bayar);
            }
        });
    }

    $scope.handleClickProsesPayment = function(){
        if($scope.total_bayar <=0){
            swal({title: "Total Bayar Tidak boleh kurang dari 0 ",text:'',type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        }else{
            $('#m_proses_bayar').modal('show');
            $scope.form_transfer = {};
            $scope.form_transfer.nominal = $scope.total_bayar;
            $scope.form_giro = {};
            $scope.form_giro.nominal = $scope.total_bayar;
            $scope.form_giro.biaya_materai = 10000;
            $scope.form_giro.is_biaya_materai = false;
            $scope.form_giro.nominal_materai = 0;
            $scope.form_giro.selisih_bayar = 0;
            $scope.form_tunai = {};
            $scope.form_tunai.nominal = $scope.total_bayar;
            $scope.input.transfer = [];
            $scope.input.tunai = [];
            $scope.input.giro = [];
        }
    }

    $scope.handleClickTambahPembayaran = function(){
        transfer = angular.copy($scope.form_transfer);
        $scope.input.transfer.push(transfer);
        $scope.form_transfer = {};
        $scope.form_transfer.nominal = $scope.total_bayar;
    }

    $scope.handleClickTambahPembayaranGiro = function(){
        giro = angular.copy($scope.form_giro);
        giro.terima_giro = $('#terima_giro').val();
        giro.jatuh_tempo = $('#jatuh_tempo').val();
        $scope.input.giro.push(giro);
        $scope.form_giro = {};
        $scope.form_giro.nominal = $scope.total_bayar;
        $scope.form_giro.biaya_materai = 10000;
        $scope.form_giro.is_biaya_materai = false;
        $scope.form_giro.nominal_materai = 0;
        $scope.form_giro.selisih_bayar = 0;
        console.log($scope.input.giro);
    }

    
    $scope.total_transfer = 0;
    $scope.getTotalTransfer = function() {
        var total = 0;
        angular.forEach($scope.input.transfer, function(item) {
            total += parseFloat(item.nominal) || 0;
        });
        $scope.total_transfer = total;
        return total;
    };

    $scope.total_giro = 0;
    $scope.getTotalGiro = function() {
        var total = 0;
        angular.forEach($scope.input.giro, function(item) {
            total += parseFloat(item.nominal) || 0;
        });
        $scope.total_giro = total;
        return total;
    };

    $scope.hitung_biaya_materai = function() {
        materai = ($scope.form_giro.is_biaya_materai)?$scope.form_giro.biaya_materai:0;
        $scope.form_giro.nominal_materai = $scope.form_giro.nominal + materai;
        $scope.form_giro.selisih_bayar = $scope.form_giro.nominal_materai - $scope.total_bayar;
    }

    $scope.simpan_pembayaran_hutang = function(){
        switch ($scope.input.metode_bayar) {
            case 'TRANSFER':
                if($scope.total_bayar !=$scope.total_transfer){
                    swal({title: "Total Transfer tidak sama dengan total bayar ",text:'',type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
                    return false;
                }
                break;
            case 'GIRO':
                if($scope.total_bayar !=$scope.total_giro){
                    swal({title: "Total Giro tidak sama dengan total bayar  ",text:'',type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
                    return false;
                }
                break;
            case 'TUNAI':
                if($scope.total_bayar !=$scope.form_tunai.nominal){
                    swal({title: "Total Pembayaran tidak sama dengan total tagihan  ",text:'',type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
                    return false;
                }
                break;
            default:
                console.log("Nilai tidak diketahui");
        }
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        let url = ($scope.edit)
            ? ""
            : "{{ route('finance.pembayaran_piutang_customer.insert') }}";

        if ($scope.edit) {
            url = url.replace(':uuid', $scope.input.uuid);
        }
        $scope.input.tanggal_bayar     = $('#tanggal_bayar').val();
        $scope.input.total_bayar = $scope.total_bayar;
        $scope.input.total_hutang = $scope.total_hutang;
        $scope.input.total_piutang = $scope.total_piutang;
        if($scope.input.metode_bayar=='TUNAI'){
            $scope.form_tunai.tanggal_bayar = $('#waktu_bayar_tunai').val()
            $scope.input.tunai.push($scope.form_tunai);
        }
        console.log($scope.input);
        $scope.input.piutang = $scope.input.piutang.filter(e => e.checked == true);
        $http.post(url,$scope.input)
        .then(function(res){
            if(res.data.success){
                swal({
                    title: "Tersimpan ",text: "Data PO berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                }).then(function(){
                    $('#m_proses_bayar').modal('hide');
                    table.draw();
                    $scope.input.uuid = res.data.data.uuid;
                    $scope.get_detail(res.data.data.uuid);
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
});
</script>