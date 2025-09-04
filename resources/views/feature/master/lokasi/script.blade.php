<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        
    });
});


$(document).ready(function() {
    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/lokasi/data') }}",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        columns: [
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formLokasi')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $('#formLokasi').submit(function(e) {
        e.preventDefault();
        var uuid = $('#uuid').val();
        var url = uuid ? '/lokasi/update/' + uuid : '/lokasi/store';
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                $('#m_create').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr) {
                alert('Gagal simpan data');
            }
        });
    });
});

function editLokasi(uuid) {
    $.get('/lokasi/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode').val(res.kode);
        $('#nama').val(res.nama);
        $('#alamat').val(res.alamat);
        $('#m_create').modal('show');
    });
}

function deleteLokasi(uuid) {
    if(confirm('Yakin hapus lokasi ini?')) {
        $.ajax({
            url: '/lokasi/delete/' + uuid,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                $('#viewtabel').DataTable().ajax.reload();
            }
        });
    }
}
</script>