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
    // $scope.coa = [
    //     { "kode_akun": "1",   "nama_akun": "Aset",                "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": null },
    //     { "kode_akun": "11",  "nama_akun": "Aset Lancar",         "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
    //     { "kode_akun": "111", "nama_akun": "Kas",                 "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
    //     { "kode_akun": "112", "nama_akun": "Piutang Usaha",       "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
    //     { "kode_akun": "12",  "nama_akun": "Aset Tetap",          "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
    //     { "kode_akun": "121", "nama_akun": "Peralatan",           "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "12" },
    //     { "kode_akun": "2",   "nama_akun": "Liabilitas",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": null },
    //     { "kode_akun": "21",  "nama_akun": "Utang Usaha",         "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
    //     { "kode_akun": "22",  "nama_akun": "Utang Bank",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
    //     { "kode_akun": "3",   "nama_akun": "Ekuitas",             "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": null },
    //     { "kode_akun": "31",  "nama_akun": "Modal Pemilik",       "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
    //     { "kode_akun": "32",  "nama_akun": "Laba Ditahan",        "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
    //     { "kode_akun": "4",   "nama_akun": "Pendapatan",          "tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": null },
    //     { "kode_akun": "41",  "nama_akun": "Pendapatan Penjualan","tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": "4" },
    //     { "kode_akun": "5",   "nama_akun": "Beban",               "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": null },
    //     { "kode_akun": "51",  "nama_akun": "Beban Gaji",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" },
    //     { "kode_akun": "52",  "nama_akun": "Beban Sewa",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" }
    // ]
});


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/coa/data",
        columns: [
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'tipe', name: 'tipe' },
            { data: 'pos_laporan', name: 'pos_laporan' },
            { data: 'saldo_normal', name: 'saldo_normal' },
            { data: 'kode_parent', name: 'kode_parent' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formCoa')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formCoa").validate({
        rules: {
            kode: { required: !0, },
            nama: { required: !0, },
            tipe: { required: !0, },
            pos_laporan: { required: !0, },
            saldo_normal: { required: !0, },
            kode_parent: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formCoa", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/coa/update/' + uuid : '/coa/store';
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: url,
                method: 'POST',
                data: $(form).serialize(),
                success: function(res) {
                    Swal.close();
                    if(res.success) {
                        Swal.fire('Sukses', 'Data berhasil disimpan!', 'success');
                        $('#m_create').modal('hide');
                        table.ajax.reload();
                    } else {
                        Swal.fire('Gagal', 'Data gagal disimpan!', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data!', 'error');
                }
            });
        }
    });
});

function editCoa(uuid) {
    $.get('/coa/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode').val(res.kode);
        $('#nama').val(res.nama);
        $('#tipe').val(res.tipe);
        $('#pos_laporan').val(res.pos_laporan);
        $('#saldo_normal').val(res.saldo_normal);
        $('#kode_parent').val(res.kode_parent);
        $('#m_create').modal('show');
    });
}

function deleteCoa(uuid) {
    Swal.fire({
        title: 'Hapus COA',
        text: 'Yakin hapus COA ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: '/coa/delete/' + uuid,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    Swal.close();
                    if(res.success) {
                        Swal.fire('Berhasil', 'Data berhasil dihapus!', 'success');
                        $('#m_create').modal('hide');
                        $('#viewtabel').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Gagal', 'Data gagal dihapus!', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data!', 'error');
                }
            });
        }
    });
}
</script>