<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        setTimeout(function(){
        $("#viewtabel").DataTable({
                scrollY: "50vh",
                scrollX: !0,
                scrollCollapse: !0,
                columnDefs: [{
                    targets: -1,
                    title: "Actions",
                    orderable: !1,
                    render: function(a, e, t, n) {
                        return `<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>
                        <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove"></i></a>`
                    }
                }],
            })
        }, 500);
    });
    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
    }
    $scope.kembali = function(){
        $scope.form = "list";
    }
    $scope.cari_supplier = function(){
        $('#m_supplier').modal('show');
    }
    $scope.coa = [
        { "kode_akun": "1",   "nama_akun": "Aset",                "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": null },
        { "kode_akun": "11",  "nama_akun": "Aset Lancar",         "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
        { "kode_akun": "111", "nama_akun": "Kas",                 "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
        { "kode_akun": "112", "nama_akun": "Piutang Usaha",       "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
        { "kode_akun": "12",  "nama_akun": "Aset Tetap",          "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
        { "kode_akun": "121", "nama_akun": "Peralatan",           "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "12" },
        { "kode_akun": "2",   "nama_akun": "Liabilitas",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": null },
        { "kode_akun": "21",  "nama_akun": "Utang Usaha",         "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
        { "kode_akun": "22",  "nama_akun": "Utang Bank",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
        { "kode_akun": "3",   "nama_akun": "Ekuitas",             "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": null },
        { "kode_akun": "31",  "nama_akun": "Modal Pemilik",       "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
        { "kode_akun": "32",  "nama_akun": "Laba Ditahan",        "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
        { "kode_akun": "4",   "nama_akun": "Pendapatan",          "tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": null },
        { "kode_akun": "41",  "nama_akun": "Pendapatan Penjualan","tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": "4" },
    ]
});
</script>