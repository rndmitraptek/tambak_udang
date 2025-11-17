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
            $('#lokasi_id').append('<option value="'+lokasi.id_lokasi+'">'+lokasi.nama_lokasi+'</option>');
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
                $('#blok_id').append('<option value="'+blok.id_blok+'">'+blok.nama_blok+'</option>');
            });
        });
    }

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/petak/data",
        order: [[6, 'desc']],
        columns: [
            { data: 'nama_lokasi', title: 'nama_lokasi' },
            { data: 'nama_blok', title: 'nama_blok' },
            { data: 'nama_petak', title: 'nama_petak' },
            {
                data: 'luas_petak',
                title: 'luas_petak',
                render: function(data, type, row) {
                    return  parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', title: 'keterangan' },
            { data: 'created_by_name', title: 'Created By', orderable: false, searchable: false },
            { data: 'created_at_formatted', name: 'created_at', title: 'Created At', searchable: false },
            { data: 'updated_by_name', title: 'Updated By', orderable: false, searchable: false },
            { data: 'updated_at_formatted', title: 'Updated At', orderable: false, searchable: false },
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
            nama_petak: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formPetak", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/petak/update/' + uuid : '/petak/store';
            swal({title: "Processing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
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
        $('#nama_petak').val(res.nama_petak);
        $('#luas_petak').val(res.luas_petak);
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
            swal({title: "Processing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
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