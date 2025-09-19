<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
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
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.get('/setup-siklus/lokasi-list', function(res) {
        res.forEach(function(lokasi) {
            $('#lokasi_id').append('<option value="'+lokasi.id_lokasi+'">'+lokasi.nama_lokasi+'</option>');
        });
    });
    
    // Inisialisasi sekali di awal
    var tablePetak = $('#viewtabelpetak').DataTable({
        paging: false,
        info: false,
        searching: false,
        processing: true,
        serverSide: true,
        ajax: "/setup-siklus/petak-list/?lokasi_id=0", // default kosong
        columns: [
            { 
                data: 'id_petak', 
                name: 'id_petak',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="petak-check" value="'+data+'">';
                }
            },
            { data: 'nama_blok', name: 'nama_blok' },
            { data: 'nama_petak', name: 'nama_petak' },
            { 
                data: 'luas_petak', 
                name: 'luas_petak',
                render: function(data, type, row) {
                    return  parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', name: 'keterangan' },
        ]
    });

    // Saat lokasi berubah, update URL dan reload
    $('#lokasi_id').on('change', function() {
        let lokasiId = $(this).val();
        tablePetak.ajax.url("/setup-siklus/petak-list/?lokasi_id=" + lokasiId).load();
    });

    // checklist all / unchecklist all
    $('#checkAllPetak').on('change', function() {
        let checked = $(this).is(':checked');
        $('.petak-check').prop('checked', checked);
    });

    // pastikan kalau ada reload data, event binding tetap jalan
    $('#viewtabelpetak').on('draw.dt', function() {
        $('#checkAllPetak').prop('checked', false);
    });

    $('#tanggal_mulai, #tanggal_selesai').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/setup-siklus/data",
        columns: [
            { data: 'nama_siklus', name: 'nama_siklus' },
            { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'tanggal_mulai', name: 'tanggal_mulai' },
            { data: 'tanggal_selesai', name: 'tanggal_selesai' },
            { data: 'catatan', name: 'catatan' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formSiklus')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $("#formSiklus").validate({
        rules: {
            lokasi_id: { required: !0, },
            nama_siklus: { required: !0, },
            tanggal_mulai: { required: !0, }
        },
        invalidHandler: function(e, r) {
            mUtil.scrollTo("formSiklus", -200)
        },
        submitHandler: function(form) {
            var uuid = $('#uuid').val();
            var url = uuid ? '/setup-siklus/update/' + uuid : '/setup-siklus/store';

            var payload = $(form).serializeArray();

            // ambil semua petak terpilih, push sebagai array
            $('.petak-check:checked').each(function() {
                payload.push({ name: 'petak_id[]', value: $(this).val() });
            });
            
            swal({title: "Processing...!",text: "Please Wait",
                onOpen: function() {
                    swal.showLoading()
                }
            })
            $.ajax({
                url: url,
                method: 'POST',
                data: payload,
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

function editSiklus(uuid) {
    $.get('/setup-siklus/show/' + uuid, function(res) {
        // isi field biasa
        $('#uuid').val(res.uuid);
        $('#lokasi_id').val(res.lokasi_id).trigger('change');
        $('#nama_siklus').val(res.nama_siklus);
        $('#tanggal_mulai').val(res.tanggal_mulai);
        $('#tanggal_selesai').val(res.tanggal_selesai);
        $('#catatan').val(res.catatan);

        // reset checkAll
        $('#checkAllPetak').prop('checked', false);

        // simpan daftar petak_id yang sudah ada di siklus
        let selectedPetak = res.petak.map(p => p.id_petak);

        // tunggu datatable petak reload berdasarkan lokasi
        $('#viewtabelpetak').one('draw.dt', function() {
            // centang petak sesuai data
            $('.petak-check').each(function() {
                if (selectedPetak.includes(parseInt($(this).val()))) {
                    $(this).prop('checked', true);
                }
            });
        });

        // reload tabel petak sesuai lokasi yang dipilih
        $('#viewtabelpetak').DataTable()
            .ajax.url("/setup-siklus/petak-list/?lokasi_id=" + res.lokasi_id)
            .load();

        $('#m_create').modal('show');
    });
}

function deleteSiklus(uuid) {
    Swal.fire({
        title: 'Hapus Setup Siklus',
        text: 'Yakin hapus data ini?',
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
                url: '/setup-siklus/delete/' + uuid,
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