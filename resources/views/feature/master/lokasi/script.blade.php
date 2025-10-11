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

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('lokasi.data') }}",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        columns: [
            { data: 'kode_lokasi', name: 'kode_lokasi' },
            { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'alamat_lokasi', name: 'alamat_lokasi' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formLokasi')[0].reset();
        $('#uuid').val('');
        $('#kode_lokasi').val("{{ \App\Helpers\GeneradeNomorHelper::sort('lokasi'); }}");
        $('#m_create').modal('show');
    });

    $("#formLokasi").validate({
        rules: {
            kode_lokasi: { required: !0, },
            nama_lokasi: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formLokasi", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid
                ? '{{ route("lokasi.update", ":uuid") }}'.replace(':uuid', uuid)
                : '{{ route("lokasi.store") }}';
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
                        swal({
                            title: "Tersimpan ",text: "Data berhasil tersiman!",type: "success",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                        }).then(function(){
                            $('#m_create').modal('hide');
                        })
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Gagal ",text: res.data.message,type: "warning",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({
                        title: "Gagal ",text: 'Terjadi kesalahan saat menyimpan data!',type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    });
                }
            });
        }
    });
});

function editLokasi(uuid) {
    var url = '{{ route("lokasi.show", ":uuid") }}'.replace(':uuid', uuid);
    $.get(url, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode_lokasi').val(res.kode_lokasi);
        $('#nama_lokasi').val(res.nama_lokasi);
        $('#alamat_lokasi').val(res.alamat_lokasi);
        $('#m_create').modal('show');
    });
}


function deleteLokasi(uuid) {
    var url = '{{ route("lokasi.delete", ":uuid") }}'.replace(':uuid', uuid);
    Swal.fire({
        title: 'Hapus Lokasi',
        text: 'Yakin hapus lokasi ini?',
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