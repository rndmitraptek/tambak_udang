<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat_customer"));
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
        ajax: "/customer/data",
        columns: [
            { data: 'kode_customer', name: 'kode_customer' },
            { data: 'nama_customer', name: 'nama_customer' },
            { data: 'alamat_customer', name: 'alamat_customer' },
            { data: 'telepon_customer', name: 'telepon_customer' },
            { data: 'email_customer', name: 'email_customer' },
            { data: 'catatan', name: 'catatan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formCustomer')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formCustomer").validate({
        rules: {
            kode_customer: { required: !0, },
            nama_customer: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formCustomer", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/customer/update/' + uuid : '/customer/store';
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

function editCustomer(uuid) {
    $.get('/customer/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode_customer').val(res.kode_customer);
        $('#nama_customer').val(res.nama_customer);
        $('#alamat_customer').val(res.alamat_customer);
        $('#telepon_customer').val(res.telepon_customer);
        $('#email_customer').val(res.email_customer);
        $('#catatan').val(res.catatan);
        $('#m_create').modal('show');
    });
}

function deleteCustomer(uuid) {
    Swal.fire({
        title: 'Hapus Customer',
        text: 'Yakin hapus customer ini?',
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
                url: '/customer/delete/' + uuid,
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