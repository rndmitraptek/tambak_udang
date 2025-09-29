<script>
app.controller("myCtrl", function($scope,$http) {
    var table;
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("finance.penaburan.datatable") }}',
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columns: [
                { data: 'no_penaburan_benur', title: 'No Penaburan benur' },
                { data: 'tanggal_penaburan', title: 'Tanggal Penaburan' },
                { data: 'no_po', title: 'No PO' },
                { data: 'nama_supplier', title: 'Supplier' },
                { data: 'nama_lokasi', title: 'Lokasi' },
                { data: 'nama_siklus', title: 'Siklus' },
                { data: 'keterangan', title: 'keterangan' },
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            url = "{{ route('finance.penaburan.get_detail',':uuid') }}"
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
                    url = "{{ route('finance.penaburan.delete',':uuid') }}";
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

    $scope.data_petak =[];
    $scope.detail = [];

    $scope.poColumns = [
        { data: 'no_po', title: 'No PO' },
        { data: 'tanggal_po', title: 'Tanggal PO' },
        { data: 'nama_supplier', title: 'Supplier' },
        { data: 'nama_lokasi', title: 'Lokasi' },
        { data: 'nama_siklus', title: 'Siklus'},
        { data: 'qty', title: 'Qty' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
        { data: 'harga_satuan', title: 'Harga Satuan' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
        { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
    ];
    
    $scope.selectPo = function(x){
        console.log(x);
        $scope.input.no_po = x.no_po;
        $scope.input.uuid_po = x.uuid;
        $scope.input.nama_supplier = x.nama_supplier;
        $scope.input.uuid_supplier = x.uuid_supplier;
        $scope.input.nama_lokasi = x.nama_lokasi;
        $scope.input.uuid_lokasi = x.uuid_lokasi;
        $scope.input.nama_siklus = x.nama_siklus;
        $scope.input.uuid_siklus = x.uuid_siklus;
        url = "{{ route('finance.penaburan.get_petak',':uuid') }}"
        url = url.replace(':uuid', x.uuid_siklus)
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.data_petak = res.data.data;
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
    $scope.cari_po = function(){
        $('#lookup_po').modal('show');
    }
    $scope.add_petak = function(){
        $('#m_petak').modal('show');
    }
    $scope.add_detail = function(){
        console.log($scope.data_petak);
        $scope.data_petak.forEach(function(item, index) {
            if(item.checked){
                $scope.data_petak[index].is_add = true;
                $scope.detail.push({
                    blok            : item.blok,
                    petak           : item.petak,
                    uuid_petak      : item.uuid,
                    uuid_benur      : null,
                    kode_benur      : '',
                    jenis_benur     : '',
                    harga_bruto     : 0,
                    jumlah_bruto    : 0,
                    subtotal_bruto  : 0,
                    harga_neto      : 0,
                    jumlah_neto     : 0,
                    subtotal_neto   : 0,
                    harga_actual    : 0,
                    jumlah_actual   : 0,
                    subtotal_actual : 0
                })
            }else{
                $scope.data_petak[index].is_add = false;
            }
        });
        $('#m_petak').modal('hide');
    }

    $scope.benurColumns = [
        { data: 'kode_supplier', title: 'Kode Benur' },
        { data: 'jenis', title: 'Jenis Benur' },
        { data: 'harga', title: 'Harga' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
    ];
    $scope.selectBenur = function(row){
        console.log(row);
        $scope.detail[$scope.index_detail].uuid_benur = row.uuid;
        $scope.detail[$scope.index_detail].kode_benur = row.kode_supplier;
        $scope.detail[$scope.index_detail].jenis_benur = row.jenis;
        $scope.detail[$scope.index_detail].harga_bruto = row.harga;
        $scope.detail[$scope.index_detail].harga_neto = row.harga;
        $scope.detail[$scope.index_detail].harga_actual = row.harga;
        $scope.$apply();
    }
    $scope.index_detail = null;
    $scope.change_benur = function(index){
        $scope.index_detail = index;
        $('#lookup_benur').modal('show');
    }
    $scope.total_harga_bruto = 0;
    $scope.total_harga_neto = 0;
    $scope.total_harga_actual = 0;
    $scope.total_jumlah_bruto = 0;
    $scope.total_jumlah_neto = 0;
    $scope.total_jumlah_actual = 0;
    $scope.hitung=function(item){
        $scope.total_harga_bruto = 0;
        $scope.total_harga_neto = 0;
        $scope.total_harga_actual = 0;
        $scope.total_jumlah_bruto = 0;
        $scope.total_jumlah_neto = 0;
        $scope.total_jumlah_actual = 0;
        $scope.detail.forEach(function(detail, index) {
            detail.subtotal_bruto = parseInt(detail.harga_bruto) * parseInt(detail.jumlah_bruto);
            detail.subtotal_neto = parseInt(detail.harga_neto) * parseInt(detail.jumlah_neto);
            detail.subtotal_actual = parseInt(detail.harga_actual) * parseInt(detail.jumlah_actual);
            $scope.total_harga_bruto = $scope.total_harga_bruto + detail.subtotal_bruto;
            $scope.total_harga_neto = $scope.total_harga_neto + detail.subtotal_neto;
            $scope.total_harga_actual = $scope.total_harga_actual + detail.subtotal_actual;
            $scope.total_jumlah_bruto = $scope.total_jumlah_bruto + parseInt(detail.jumlah_bruto);
            $scope.total_jumlah_neto = $scope.total_jumlah_neto + parseInt(detail.jumlah_neto);
            $scope.total_jumlah_actual = $scope.total_jumlah_actual + parseInt(detail.jumlah_actual);
        });
    }

    $scope.simpan = function(){
        $("#formInput").submit();
    }

    $("#formInput").validate({
        rules: {
            no_penaburan_benur: {
                required: true,
                digits: true
            },
            tanggal_penaburan: {
                required: true
            },
            no_po: {
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
                ? "{{ route('finance.penaburan.update', ':uuid') }}"
                : "{{ route('finance.penaburan.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal_penaburan      = $('#tanggal_penaburan').val();
            $scope.input.detail                 = $scope.detail;
            $scope.input.jumlah_bruto           = $scope.total_jumlah_bruto
            $scope.input.total_nominal_bruto    = $scope.total_harga_bruto
            $scope.input.jumlah_netto           = $scope.total_jumlah_neto
            $scope.input.total_nominal_netto    = $scope.total_harga_neto
            $scope.input.jumlah_actual          = $scope.total_jumlah_actual
            $scope.input.total_nominal_actual   = $scope.total_harga_actual

            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Penaburan berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
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