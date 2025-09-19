<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        
    });
    
});

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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/benur/data",
        columns: [
            { data: 'kode_benur', name: 'kode_benur' },
            { data: 'kode_supplier', name: 'kode_supplier' },
            { data: 'jenis_benur', name: 'jenis_benur' },
            { 
                data: 'harga_benur', 
                name: 'harga_benur',
                render: function(data, type, row) {
                    // Format angka ke Rupiah
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formBenur')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formBenur").validate({
        rules: {
            kode_benur: { required: !0, },
            kode_supplier: { required: !0, },
            jenis_benur: { required: !0, },
            harga_benur: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formBenur", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/benur/update/' + uuid : '/benur/store';
            
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

function editBenur(uuid) {
    $.get('/benur/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode_benur').val(res.kode_benur);
        $('#kode_supplier').val(res.kode_supplier);
        $('#jenis_benur').val(res.jenis_benur);
        $('#harga_benur').val(res.harga_benur);
        $('#keterangan').val(res.keterangan);
        $('#m_create').modal('show');
    });
}

function deleteBenur(uuid) {
    Swal.fire({
        title: 'Hapus Benur',
        text: 'Yakin hapus benur ini?',
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
                url: '/benur/delete/' + uuid,
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