<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        
    });
    $scope.tes = "tes";
});

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load data lokasi untuk select
    $.get('{{ route('blok.lokasi-list') }}', function(res) {
        $('#lokasi_id').empty();
        res.forEach(function(lokasi) {
            $('#lokasi_id').append('<option value="'+lokasi.id_lokasi+'">'+lokasi.nama_lokasi+'</option>');
        });
    });

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('blok.data') }}",
        columns: [
            { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'nama_blok', name: 'nama_blok' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formBlok')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formBlok").validate({
        rules: {
            lokasi_id: { required: !0, },
            nama_blok: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formBlok", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid
                ? '{{ route("blok.update", ":uuid") }}'.replace(':uuid', uuid)
                : '{{ route("blok.store") }}';
            swal({title: "Presesing...!",text: "Please Wait",
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
                        Swal.fire('Tersimpan', 'Data berhasil tersimpan!', 'success');
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

function editBlok(uuid) {
    var url = '{{ route("blok.show", ":uuid") }}'.replace(':uuid', uuid);
    $.get(url, function(res) {
        $('#uuid').val(res.uuid);
        $('#lokasi_id').val(res.lokasi_id);
        $('#nama_blok').val(res.nama_blok);
        $('#keterangan').val(res.keterangan);
        $('#m_create').modal('show');
    });
}

function deleteBlok(uuid) {
    var url = '{{ route("blok.delete", ":uuid") }}'.replace(':uuid', uuid);
    Swal.fire({
        title: 'Hapus Blok',
        text: 'Yakin hapus blok ini?',
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
                url: url,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    Swal.close();
                    if(res.success) {
                        Swal.fire('Berhasil', 'Data berhasil dihapus!', 'success');
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