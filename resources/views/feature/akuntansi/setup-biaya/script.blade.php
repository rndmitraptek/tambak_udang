<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        autosize($("#catatan"));
        
    });
    $scope.coa = [
        { "kode_akun": "5",   "nama_akun": "Beban",               "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": null },
        { "kode_akun": "51",  "nama_akun": "Beban Gaji",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" },
        { "kode_akun": "52",  "nama_akun": "Beban Sewa",          "tipe_akun": "Expense", "pos_laporan": "Laba Rugi","saldo_normal": "Debit", "kode_parent": "5" }
    ];

    $scope.lokasi = [];       // hasil akhir selalu array
    $scope.lokasiSingle = null;

    $scope.$watch('kelompok', function (v) {
    if (v === 'Perlokasi') {
        // kalau sebelumnya ada data array, isi ulang ke single
        $scope.lokasiSingle = $scope.lokasi.length ? $scope.lokasi[0] : null;
        $scope.lokasi = $scope.lokasiSingle ? [$scope.lokasiSingle] : [];
    } else if (v === 'Gabungan') {
        // pastikan selalu array
        if (!Array.isArray($scope.lokasi)) {
        $scope.lokasi = [];
        }
    } else {
        $scope.lokasi = [];
        $scope.lokasiSingle = null;
    }
    });

    
});

// app.controller("SetupBiayaCtrl", function($scope, $http) {
//     $scope.kelompok = '';
//     $scope.lokasiList = [];
//     $scope.lokasi = '';
//     $scope.periode = false;

//     // Load lokasi dari backend
//     $http.get('/setup-biaya/lokasi-list').then(function(res) {
//         $scope.lokasiList = res.data;
//     });

//     $scope.tambahLokasi = function() {
//         // Implementasi tambah lokasi gabungan
//         $scope.lokasiGabungan.push($scope.lokasi);
//     };
// });

function tambah(){
    hasil = 20
    grade = grade(hasil);
    alert(grade);
}

function grade(nilai){
    if(nilai >= 90){
        return "A"
    }else if(nilai >= 80){
        return "B"
    }else if(nilai >= 70){
        return "C"
    }else if(nilai >= 60){
        return "D"
    }else{
        return "E"
    }
}


$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Load COA, lokasi, petak
    $.get('/setup-biaya/coa-list', function(res) {
        $('#coa_id').empty();
        res.forEach(function(coa) {
            $('#coa_id').append('<option value="'+coa.id+'">'+coa.kode+' - '+coa.nama+'</option>');
        });
    });
    $.get('/setup-biaya/lokasi-list', function(res) {
        $('#lokasi_id_single').empty();
        res.forEach(function(lokasi) {
            $('#lokasi_id_single').append('<option value="'+lokasi.id+'">'+lokasi.nama+'</option>');
        });
        $('#lokasi_id_multi').empty();
        res.forEach(function(lokasi) {
            $('#lokasi_id_multi').append('<option value="'+lokasi.id+'">'+lokasi.nama+'</option>');
        });

        // Panggil setelah $scope.lokasiList diisi
        setTimeout(function() {
        $('#lokasi_id_multi').select2({
            placeholder: "Pilih Lokasi",
            allowClear: true,
            width: '100%'
        });
        }, 100);
    });
    $.get('/setup-biaya/petak-list', function(res) {
        $('#petak_id').empty();
        res.forEach(function(petak) {
            $('#petak_id').append(`<option value="${petak.id}">${petak.nama_lokasi}-${petak.nama_blok}-${petak.nama_petak}</option>`);
        });
    });

    // Tampilkan/hidden lokasi sesuai kelompok
    $('#kelompok').on('change', function() {
        var val = $(this).val();
        if(val === 'Gabungan' || val === 'Perlokasi') {
            $('#petak-group').hide();
            $('#lokasi-group').show();
            if(val === 'Gabungan') {
                $('#btnTambahLokasi').show();
            } else {
                $('#btnTambahLokasi').hide();
            }
        } else {
            $('#lokasi-group').hide();
            $('#petak-group').show();
        }
    });

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/setup-biaya/data",
        columns: [
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'kelompok', name: 'kelompok' },
            // { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'periode', name: 'periode' },
            { data: 'coa', name: 'coa' },
            { 
                data: 'nominal', 
                name: 'nominal',
                render: function(data) {
                    return data ? 'Rp ' + parseInt(data).toLocaleString('id-ID') : '-';
                }
            },
            { data: 'catatan', name: 'catatan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formSetupBiaya')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
        // $('#lokasi-group').hide();
        // $('#btnTambahLokasi').hide();
    });

    $("#formSetupBiaya").validate({
        rules: {
            kode: { required: !0, },
            nama: { required: !0, },
            kelompok: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formSetupBiaya", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/setup-biaya/update/' + uuid : '/setup-biaya/store';
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
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

function editBiaya(uuid) {
    $.get('/setup-biaya/show/' + uuid, function(res) {
        var scope = angular.element($('#m_create')).scope();
        scope.$apply(function () {
            // isi field yang dihandle Angular
            scope.kelompok = res.kelompok;
            scope.periode = res.periode == 1;
            scope.nominal = res.nominal;
            scope.catatan = res.catatan;

            // lokasi
            if (res.kelompok === 'Perlokasi') {
                if (res.lokasi && res.lokasi.length > 0) {
                    scope.lokasiSingle = res.lokasi[0].id;
                    scope.lokasi = [res.lokasi[0].id];
                    $('#lokasi_id_single').val(scope.lokasiSingle).trigger('change');
                }
            } else if (res.kelompok === 'Gabungan') {
                var lokasiIds = res.lokasi.map(l => l.id);
                scope.lokasi = lokasiIds;
                $('#lokasi_id_multi').val(lokasiIds).trigger('change'); // sync select2
            } else if (res.kelompok === 'Perpetak') {
                scope.petak = res.petak_id;
                $('#petak_id').val(res.petak_id).trigger('change');
            }
        });
        
        // isi field biasa (jQuery langsung, karena tidak ada ng-model)
        $('#uuid').val(res.uuid);
        $('#kode').val(res.kode);
        $('#nama').val(res.nama);
        $('#nominal').val(res.nominal);
        $('#coa_id').val(res.coa_id).trigger('change');
        $('#catatan').val(res.catatan);
        // periode (checkbox)
        $('input[name="periode"]').prop('checked', res.periode == 1);

        $('#m_create').modal('show');
    });
}

function deleteBiaya(uuid) {
    Swal.fire({
        title: 'Hapus Setup Biaya',
        text: 'Yakin hapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            $.ajax({
                url: '/setup-biaya/delete/' + uuid,
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