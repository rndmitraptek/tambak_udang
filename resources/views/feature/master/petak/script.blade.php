<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        
    });
});

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load lokasi untuk select
    $.get('/petak/lokasi-list', function(res) {
        $('#lokasi_id').empty();
        res.forEach(function(lokasi) {
            $('#lokasi_id').append('<option value="'+lokasi.id+'">'+lokasi.nama+'</option>');
        });
        // Trigger blok load for first lokasi
        var firstLokasi = $('#lokasi_id').val();
        if(firstLokasi) loadBlok(firstLokasi);
    });

    // Saat lokasi berubah, load blok sesuai lokasi
    $('#lokasi_id').on('change', function() {
        var lokasiId = $(this).val();
        loadBlok(lokasiId);
    });

    function loadBlok(lokasiId) {
        $('#blok_id').empty();
        $.get('/petak/blok-list-by-lokasi/' + lokasiId, function(res) {
            res.forEach(function(blok) {
                $('#blok_id').append('<option value="'+blok.id+'">'+blok.nama+'</option>');
            });
        });
    }

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/petak/data",
        columns: [
            { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'nama_blok', name: 'nama_blok' },
            { data: 'nama', name: 'nama' },
            { 
                data: 'luas', 
                name: 'luas',
                render: function(data, type, row) {
                    return  parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formPetak')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formPetak").validate({
        rules: {
            lokasi_id: { required: !0, },
            blok_id: { required: !0, },
            nama: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formPetak", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/petak/update/' + uuid : '/petak/store';
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

function editPetak(uuid) {
    $.get('/petak/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#lokasi_id').val(res.lokasi_id);
        $('#blok_id').val(res.blok_id);
        $('#nama').val(res.nama);
        $('#luas').val(res.luas);
        $('#keterangan').val(res.keterangan);
        $('#m_create').modal('show');
    });
}

function deletePetak(uuid) {
    Swal.fire({
        title: 'Hapus Petak',
        text: 'Yakin hapus petak ini?',
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
                url: '/petak/delete/' + uuid,
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