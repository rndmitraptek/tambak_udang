<script>
app.controller("myCtrl", function($scope,$http,API) {
    $scope.form = { lokasi_id: null, siklus_id: null, cari: '' };
    $scope.lokasiList = [];
    $scope.siklusList = [];
    $scope.report = null;
    $scope.coaModal = { tipe: null, petak: null, label: '', rows: [], total: 0, jumlah: 0 };
    $scope.detailModal = { coa: {}, rows: [], total: 0, jumlah: 0 };

    angular.element(document).ready(function () {
        // modal bertumpuk (rincian COA -> rincian transaksi)
        $(document).on('show.bs.modal', '.modal', function () {
            var z = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', z);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', z - 1).addClass('modal-stack');
            }, 0);
        });
        $(document).on('hidden.bs.modal', '.modal', function () {
            if ($('.modal:visible').length) $('body').addClass('modal-open');
        });
    });

    function showError(title, message){
        swal({title: title, text: message, type: "error", confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"});
    }

    $http.get("{{ route('akuntansi.laba_rugi_petak.lokasi') }}").then(function(res){
        $scope.lokasiList = res.data.data;
    });

    $scope.loadSiklus = function(){
        $scope.form.siklus_id = null;
        $scope.siklusList = [];
        if (!$scope.form.lokasi_id) return;
        $http.get("{{ url('akuntansi/laba_rugi_petak/siklus') }}/" + $scope.form.lokasi_id).then(function(res){
            $scope.siklusList = res.data.data;
        });
    };

    $scope.getSummary = function(){
        if (!$scope.form.siklus_id) {
            swal({title: "Perhatian", text: "Pilih lokasi dan siklus terlebih dahulu", type: "warning", confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"});
            return;
        }
        swal({title: "Processing...!", text: "Please Wait", onOpen: function() { swal.showLoading() }});
        $http.post("{{ route('akuntansi.laba_rugi_petak.summary') }}", { siklus_id: $scope.form.siklus_id })
        .then(function(res){
            if (res.data.success) {
                $scope.report = res.data.data;
                Swal.close();
            } else {
                swal({title: "Gagal", text: res.data.message, type: "warning", confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"});
            }
        }).catch(function(error) {
            showError(error.statusText, error.data ? error.data.message : '');
        });
    };

    $scope.cariPetak = function(p){
        var q = ($scope.form.cari || '').toLowerCase();
        if (!q) return true;
        return ((p.nama_blok || '') + ' ' + (p.nama_petak || '')).toLowerCase().indexOf(q) !== -1;
    };

    // level 1: rincian per coa (petak null = semua petak)
    $scope.openCoa = function(tipe, petak){
        var src = petak || $scope.report.total;
        var rows = tipe == 'biaya' ? src.biaya_coa : src.pendapatan_coa;
        $scope.coaModal = {
            tipe: tipe,
            petak: petak,
            label: $scope.report.siklus.nama_siklus + ' — ' + (petak ? ('Blok ' + petak.nama_blok + ' / Petak ' + petak.nama_petak) : 'Semua Petak'),
            rows: rows,
            total: rows.reduce(function(a, r){ return a + Number(r.total || 0); }, 0),
            jumlah: rows.reduce(function(a, r){ return a + Number(r.jumlah || 0); }, 0)
        };
        $('#m_lr_coa').modal('show');
    };

    // level 2: rincian transaksi per coa
    $scope.openDetail = function(coa){
        swal({title: "Processing...!", text: "Please Wait", onOpen: function() { swal.showLoading() }});
        $http.post("{{ route('akuntansi.laba_rugi_petak.detail') }}", {
            siklus_id: $scope.report.siklus.id_siklus,
            tipe: $scope.coaModal.tipe,
            id_coa: coa.id_coa,
            petak_id: $scope.coaModal.petak ? $scope.coaModal.petak.petak_id : null
        })
        .then(function(res){
            if (res.data.success) {
                var rows = res.data.data;
                $scope.detailModal = {
                    coa: coa,
                    rows: rows,
                    total: rows.reduce(function(a, r){ return a + Number(r.nominal || 0); }, 0),
                    jumlah: rows.reduce(function(a, r){ return a + Number(r.jumlah || 0); }, 0)
                };
                Swal.close();
                $('#m_lr_detail').modal('show');
            } else {
                swal({title: "Gagal", text: res.data.message, type: "warning", confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"});
            }
        }).catch(function(error) {
            showError(error.statusText, error.data ? error.data.message : '');
        });
    };
});
</script>
