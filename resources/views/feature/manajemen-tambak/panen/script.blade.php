<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("panen.datatable") }}',
            scrollY: "50vh",
            scrollX: true,
            autoWidth: false,
            scrollCollapse: !0,
            columns: [
                { data: 'action', title: 'action', orderable: false, searchable: false },
                { data: 'no_panen', title: 'No Panen' },
                { data: 'tanggal_panen', title: 'Tanggal Panen' },
                { data: 'nama_siklus', title: 'Siklus' },
                { data: 'nama_lokasi', title: 'Lokasi' },
                { data: 'nama_blok', title: 'Blok' },
                { data: 'nama_petak', title: 'Petak' },
                { data: 'jenis_panen', title: 'Jenis Panen' },
                { data: 'jumlah', title: 'Jumlah' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { data: 'total', title: 'Total' ,"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
                { 
                    data: 'keterangan', 
                    title: 'Keterangan', 
                    render: function (data, type, row) {
                        if (!data) return '';
                        let shortText = data.length > 50 ? data.substr(0, 50) + '...' : data;
                        return `<span title="${data.replace(/"/g, '&quot;')}">${shortText}</span>`;
                    },
                    width: '200px'
                },
                { data: 'created_by_name', title: 'Created By' },
                { data: 'created_at_formatted', title: 'Created At' },
                { data: 'updated_by_name', title: 'Updated By' },
                { data: 'updated_at_formatted', title: 'Updated At' },
            ]
        })

        $('#viewtabel tbody').on('click', '#edit', function () {
            var tr = $(this).closest('tr');
            var x = table.row(tr).data();
            $scope.input = x;
            $scope.get_blok();
            $scope.get_petak();
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

    $scope.coa = [];
    $http.get("{{ route('panen.get_coa') }}")
    .then(function(res){
        if(res.data.success){
            $scope.coa = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.item = [];
    $http.get("{{ route('panen.get_item') }}")
    .then(function(res){
        if(res.data.success){
            $scope.item = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.payment_method = [];
    $http.get("{{ route('panen.get_payment_method') }}")
    .then(function(res){
        if(res.data.success){
            $scope.payment_method = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.siklus = [];
    $http.get("{{ route('panen.get_siklus') }}")
    .then(function(res){
        if(res.data.success){
            $scope.siklus = res.data.data;
        }
    }).catch(function(error) {
        swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
    });

    $scope.blok = [];
    $scope.get_blok = function(){
        swal({title: "Presesing...!",text: "Please Wait Load Data Blok",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('panen.get_blok',':uuid_siklus') }}";
        url = url.replace(':uuid_siklus', $scope.input.uuid_siklus);
        $http.get(url)
        .then(function(res){
            if(res.data.success){
                $scope.blok = res.data.data;
                Swal.close();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.petak = [];
    $scope.get_petak = function(){
        swal({title: "Presesing...!",text: "Please Wait Load Petak Blok",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('panen.get_petak') }}";
        $http.post(url,{
            uuid_siklus : $scope.input.uuid_siklus, 
            uuid_blok   : $scope.input.uuid_blok
        })
        .then(function(res){
            if(res.data.success){
                $scope.petak = res.data.data;
                Swal.close();
            }
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }

    $scope.customerColumns = [
        { data: 'kode_customer', title: 'Kode customer' },
        { data: 'nama_customer', title: 'Nama customer' },
        { data: 'telepon_customer', title: 'Telepon' }
    ];
    $scope.handleClickPenjualan = function(){
        $('#lookup_customer').modal('show');
    }
    $scope.detail=[]
    $scope.selectCustomer = function(param){
        console.log($scope.item);
        console.log(param)
        $scope.detail.push({
            uuid_customer       : param.uuid,
            nama_customer       : param.nama_customer,
            uuid_payment_method : '',
            uuid_item           : '',
            harga               : 0,
            jumlah              : 1,
            subtotal            : 1
        });
        $scope.$apply();
    }
    
    $scope.remove_detail = function(index,item){
        $scope.detail.splice(index, 1);
        $scope.hitung();
    }

    $scope.jumlah = 0;
    $scope.total = 0;
    $scope.hitung = function(){
        $scope.jumlah = 0;
        $scope.total = 0;
        $scope.detail.forEach(function(detail, index) {
            detail.subtotal = parseFloat(detail.jumlah) * parseFloat(detail.harga);
            $scope.jumlah = $scope.jumlah + parseFloat(detail.jumlah);
            $scope.total = $scope.total + parseFloat(detail.subtotal);
        });
    }

    $scope.form = "list";
    $scope.tambah = function(){
        $scope.input = {};
        $scope.detail = []
        $scope.form = "input";
        $scope.edit = false;
        //nomor
        $http.get("{{ route('long','panen') }}")
        .then(function(res){
            $scope.input.no_panen = res.data.nomor;
        }).catch(function(error) {
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
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
            no_panen: {
                required: true
            },
            tanggal_panen: {
                required: true
            },
            uuid_siklus: {
                required: true
            },
            uuid_blok: {
                required: true
            },
            uuid_petak: {
                required: true
            },
            jenis_panen: {
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
                ? "{{ route('panen.update', ':uuid') }}"
                : "{{ route('panen.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal_panen  = $('#tanggal_panen').val();
            $scope.input.detail         = $scope.detail;
            $scope.input.total          = $scope.total
            $scope.input.jumlah         = $scope.jumlah

            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Panen berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        //$scope.edit = true;
                        //$scope.form = "input";
                        //$scope.input.uuid = res.data.data.uuid;
                        //table.draw();
                        $scope.kembali();
                        $scope.$apply();
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