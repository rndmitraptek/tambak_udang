<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat_supplier"));
        autosize($("#catatan"));
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
        ajax: "/supplier/data",
        columns: [
            { data: 'kode_supplier', title: 'kode Supplier' },
            { data: 'nama_supplier', title: 'Nama title' },
            { data: 'alamat_supplier', title: 'Alamat title' },
            { data: 'telepon_supplier', title: 'telepon title' },
            { data: 'email_supplier', title: 'Email title' },
            { data: 'nama_perusahaan', title: 'Nama Perusahaan' },
            { data: 'catatan', title: 'catatan' },
            { data: 'created_by_name', title: 'Created By' },
            { data: 'created_at_formatted', title: 'Created At' },
            { data: 'updated_by_name', title: 'Updated By' },
            { data: 'updated_at_formatted', title: 'Updated At' },
            { data: 'actions', title: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formSupplier')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formSupplier").validate({
        rules: {
            kode_supplier: { required: !0, },
            nama_supplier: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formSupplier", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/supplier/update/' + uuid : '/supplier/store';
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

function editSupplier(uuid) {
    $.get('/supplier/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode_supplier').val(res.kode_supplier);
        $('#nama_supplier').val(res.nama_supplier);
        $('#alamat_supplier').val(res.alamat_supplier);
        $('#telepon_supplier').val(res.telepon_supplier);
        $('#email_supplier').val(res.email_supplier);
        $('#nama_perusahaan').val(res.nama_perusahaan);
        $('#catatan').val(res.catatan);
        $('#m_create').modal('show');
    });
}

function deleteSupplier(uuid) {
    Swal.fire({
        title: 'Hapus Supplier',
        text: 'Yakin hapus supplier ini?',
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
                url: '/supplier/delete/' + uuid,
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