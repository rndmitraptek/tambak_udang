<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        
    });


    var historyTable;
    $(document).on('click', '.btn-detail', function() {
        let uuid = $(this).data('uuid');

        if ($.fn.DataTable.isDataTable('#historyTable')) {
            $('#historyTable').DataTable().clear().destroy();
        }

        // swal({title: "Processing...!",text: "Please Wait",
        //     onOpen: function() {
        //         swal.showLoading()
        //     }
        // })
        historyTable = $('#historyTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/stok_pakan/show/' + uuid,
            columns: [
                { data: 'tanggal', name: 'tanggal' },
                { data: 'pakan', name: 'pakan.nama_pakan' },
                { data: 'lokasi', name: 'lokasi.nama_lokasi' },
                { data: 'transaksi', name: 'transaksi' },
                { data: 'referensi_no', name: 'referensi_no' },
                { 
                    data: 'awal', 
                    name: 'awal',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
                { 
                    data: 'masuk', 
                    name: 'masuk',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
                { 
                    data: 'keluar', 
                    name: 'keluar',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
                { 
                    data: 'saldo', 
                    name: 'saldo',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseInt(data).toLocaleString('id-ID') : '-';
                    }
                },
            ],
            order: [[0, 'desc']]
        });

        // tampilkan modal
        $('#historyModal').modal('show');

        // $http.get('/stok_pakan/show/' + uuid).then(function(res){
        //     Swal.close();
        //     if(res.data.success){
        //         let transaksi = res.data.data;
        //         console.log(transaksi);
        //         // let html = '<table class="table table-bordered">';
        //         // html += '<tr><th>Kode Pakan</th><th>Nama Pakan</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>';

        //         // transaksi.detail.forEach(function(item){
        //         //     html += `<tr>
        //         //         <td>${item.pakan.kode_pakan}</td>
        //         //         <td>${item.pakan.nama_pakan}</td>
        //         //         <td class="text-right">${Number(item.harga).toLocaleString()}</td>
        //         //         <td class="text-right">${item.jumlah}</td>
        //         //         <td class="text-right">${Number(item.subtotal).toLocaleString()}</td>
        //         //     </tr>`;
        //         // });

        //         // html += '</table>';

        //         // Swal.fire({
        //         //     title: 'Detail Transaksi ' + transaksi.no_pembelian,
        //         //     html: html,
        //         //     width: '700px'
        //         // });
        //     }
        // }).catch(function(err){
        //     Swal.fire('Error', 'Gagal mengambil detail transaksi', 'error');
        // });
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
        ajax: "/stok_pakan/data",
        columns: [
            { data: 'nama_lokasi', name: 'setup_lokasi.nama_lokasi' },
            { data: 'kode_pakan', name: 'setup_pakan.kode_pakan' },
            { data: 'nama_pakan', name: 'setup_pakan.nama_pakan' },
            { data: 'jenis_pakan', name: 'setup_pakan.jenis_pakan' },
            { data: 'merk_pakan', name: 'setup_pakan.merk_pakan' },
            { data: 'satuan_pakan', name: 'setup_pakan.satuan_pakan' },
            { 
                data: 'stok', 
                name: 'stok',
                className: 'text-right',
                searchable: false,
                render: function(data, type, row) {
                    return parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

});

</script>