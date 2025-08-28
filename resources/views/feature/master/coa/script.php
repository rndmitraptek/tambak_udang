<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        // $("#viewtabel").DataTable({
        //     scrollY: "50vh",
        //     scrollX: !0,
        //     scrollCollapse: !0,
        // })
    });
    $scope.tes = "tes";
    $scope.tambah = function(){
        console.log("tambah lokasi");
        $('#m_create').modal('show');
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
        { "kode_akun": "5",   "nama_akun": "Beban",               "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": null },
        { "kode_akun": "51",  "nama_akun": "Beban Gaji",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" },
        { "kode_akun": "52",  "nama_akun": "Beban Sewa",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" }
    ]
});
</script>