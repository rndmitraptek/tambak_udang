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
        ajax: "/pakan/data",
        columns: [
            { data: 'kode_pakan', name: 'kode_pakan' },
            { data: 'nama_pakan', name: 'nama_pakan' },
            { data: 'jenis_pakan', name: 'jenis_pakan' },
            { data: 'merk_pakan', name: 'merk_pakan' },
            { data: 'satuan_pakan', name: 'satuan_pakan' },
            { 
                data: 'harga_pakan', 
                name: 'harga_pakan',
                render: function(data, type, row) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formPakan')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formPakan").validate({
        rules: {
            kode_pakan: { required: !0, },
            nama_pakan: { required: !0, },
            jenis_pakan: { required: !0, },
            merk_pakan: { required: !0, },
            satuan_pakan: { required: !0, },
            harga_pakan: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formPakan", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/pakan/update/' + uuid : '/pakan/store';

            // ✅ Bersihkan angka ribuan sebelum serialize
            let hargaInput = $('#harga_pakan');
            let rawHarga = hargaInput.val()
                .replace(/\./g, '')   // hapus titik ribuan
                .replace(/,/g, '.');  // ubah koma jadi titik desimal
            hargaInput.val(rawHarga);
            
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

function editPakan(uuid) {
    $.get('/pakan/show/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#kode_pakan').val(res.kode_pakan);
        $('#nama_pakan').val(res.nama_pakan);
        $('#jenis_pakan').val(res.jenis_pakan);
        $('#merk_pakan').val(res.merk_pakan);
        $('#satuan_pakan').val(res.satuan_pakan);
        $('#harga_pakan').val(res.harga_pakan);
        $('#keterangan').val(res.keterangan);
        $('#m_create').modal('show');
    });
}

function deletePakan(uuid) {
    Swal.fire({
        title: 'Hapus Pakan',
        text: 'Yakin hapus pakan ini?',
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
                url: '/pakan/delete/' + uuid,
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