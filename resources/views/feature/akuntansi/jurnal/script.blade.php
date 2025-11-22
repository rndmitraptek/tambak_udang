<script>
app.controller("myCtrl", function($scope,$http,API) {
    angular.element(document).ready(function () {

        table = $("#viewtabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("akuntansi.jurnal.datatable") }}',
            scrollY: "50vh",
            scrollX: true,
            autoWidth: false,
            scrollCollapse: !0,
            columns: [
                { data: 'action', title: 'action', orderable: false, searchable: false,width:'80px' },
                { data: 'tanggal', title: 'Tanggal' },
                { data: 'no_bukti', title: 'No Bukti' },
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
                {data:'detail[0].nominal_value', title: 'Nominal',"className": "text-right",render: $.fn.dataTable.render.number( '.', ',', 0, '' )},
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
            url = "{{ route('akuntansi.jurnal.get_detail',':uuid') }}"
            url = url.replace(':uuid', x.uuid)
            swal({title: "Presesing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            $http.get(url)
            .then(function(res){
                if(res.data.success){
                    $scope.detail = res.data.data.detail;
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

        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (selected) {
            let startDate = new Date(selected.date.valueOf());
            $('#endDate').datepicker('setStartDate', startDate);
        });

        // Inisialisasi End Date
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (selected) {
            let endDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', endDate);
        });
    });
    $scope.form = "list";
    $scope.edit = false;
    $scope.input = {};
    $scope.detail = [];
    $scope.jurnal_umum = [];
    $scope.get_jurnal_umum = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.post("{{ route('akuntansi.jurnal.jurnal_umum') }}",{
            tanggal_mulai   : $('#startDate').val(),
            tanggal_selesai : $('#endDate').val()
        })
        .then(function(res){
            if(res.data.success){
                $scope.jurnal_umum = res.data.data;
                $scope.start_date = $('#startDate').val();
                $scope.end_date = $('#endDate').val();
                Swal.close();
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
    $scope.kembali = function(){
        $scope.form = "list";
    }
    $scope.history = function(){
        $scope.form = "history";
        table.draw();
        $scope.$apply();
    }
    $scope.tambah = function(){
        $scope.input = {};
        $scope.detail = [];
        $scope.form = "input";
        $scope.edit = false;
        $scope.$apply();
    }
    $scope.remove_detail = function(index,item){
        $scope.detail.splice(index, 1);
        $scope.hitung();

    }
    $scope.coaColumns = [
        { data: 'kode_coa', title: 'Kode COA' },
        { data: 'nama_coa', title: 'Nama COA' },
        { data: 'tipe_coa', title: 'tipe' },
        { data: 'pos_laporan', title: 'laporan' },
        { data: 'saldo_normal', title: 'Saldo Normal' }
    ];
    $scope.handleClickDetail = function(){
        $('#lookup_coa').modal('show');
    }
    $scope.selectCoa = function(param){
        $scope.detail.push({
            id_coa      : param.id_coa,
            kode_coa    : param.kode_coa,
            nama_coa    : param.nama_coa,
            debit       : 0,
            kredit      : 0
        });
        $scope.hitung();
        $scope.$apply();
    }
    $scope.hitung = function(){
        $scope.total_debit = 0;
        $scope.total_kredit = 0;
        $scope.detail.forEach(function(detail, index) {
            $scope.total_debit = $scope.total_debit + parseFloat(detail.debit);
            $scope.total_kredit = $scope.total_kredit + parseFloat(detail.kredit);
        });
    }
    $scope.simpan = function(){
        $("#formInput").submit();
    }
    $("#formInput").validate({
        rules: {
            no_bukti: {
                required: true
            },
            tanggal: {
                required: true
            },
            keterangan: {
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
            if($scope.total_debit!=$scope.total_kredit){
                swal({title: "Gagal ",text: 'Input Debit dan kredit tidak balance',type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
                return false;
            }
            let url = ($scope.edit)
                ? "{{ route('akuntansi.jurnal.update', ':uuid') }}"
                : "{{ route('akuntansi.jurnal.insert') }}";

            if ($scope.edit) {
                url = url.replace(':uuid', $scope.input.uuid);
            }
            $scope.input.tanggal   = $('#tanggal').val();
            $scope.input.detail    = $scope.detail;

            $http.post(url,$scope.input)
            .then(function(res){
                if(res.data.success){
                    swal({
                        title: "Tersimpan ",text: "Data Jurnal berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    }).then(function(){
                        $scope.history();
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