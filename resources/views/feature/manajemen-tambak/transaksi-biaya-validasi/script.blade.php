<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
    });
    $scope.tes = "tes";
    $scope.form = "list";
    // $('#viewtabel').on('click', '.checklist', function () {
    //     let table = $('#viewtabel').DataTable();
    //     let rowData = table.row($(this).closest('tr')).data();
    //     $scope.$apply(function () {
    //          $scope.tambah();
    //     });
    // });
    $scope.tambah = function(){
        $scope.form = "input";
    }
    $scope.kembali = function(){
        $scope.form = "list";
    }
    $scope.cari_supplier = function(){
        $('#m_supplier').modal('show');
    }
    $scope.coa = [
        { "kode_akun": "1",   "nama_akun": "Aset",                "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": null },
        { "kode_akun": "11",  "nama_akun": "Aset Lancar",         "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
        { "kode_akun": "111", "nama_akun": "Kas",                 "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
        { "kode_akun": "112", "nama_akun": "Piutang Usaha",       "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "11" },
        { "kode_akun": "12",  "nama_akun": "Aset Tetap",          "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "1" },
        { "kode_akun": "121", "nama_akun": "Peralatan",           "tipe_akun": "Asset",   "pos_laporan": "Neraca",   "saldo_normal": "Debit",  "kode_parent": "12" },
        { "kode_akun": "2",   "nama_akun": "Liabilitas",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": null },
        { "kode_akun": "21",  "nama_akun": "Utang Usaha",         "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
        { "kode_akun": "22",  "nama_akun": "Utang Bank",          "tipe_akun": "Liability","pos_laporan": "Neraca", "saldo_normal": "Kredit", "kode_parent": "2" },
        { "kode_akun": "3",   "nama_akun": "Ekuitas",             "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": null },
        { "kode_akun": "31",  "nama_akun": "Modal Pemilik",       "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
        { "kode_akun": "32",  "nama_akun": "Laba Ditahan",        "tipe_akun": "Equity",  "pos_laporan": "Neraca",  "saldo_normal": "Kredit", "kode_parent": "3" },
        { "kode_akun": "4",   "nama_akun": "Pendapatan",          "tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": null },
        { "kode_akun": "41",  "nama_akun": "Pendapatan Penjualan","tipe_akun": "Revenue", "pos_laporan": "Laba Rugi","saldo_normal": "Kredit","kode_parent": "4" },
    ]
});


var tablePetak;

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#tanggal_mulai, #tanggal_selesai, #tanggal_transaksi').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $.getJSON('/transaksi-biaya/biaya-list', function(data) {
        let biayaDropdown = $("#biaya-dropdown");
        biayaDropdown.empty();
        biayaDropdown.append(`<option value="">-- Pilih Biaya --</option>`);

        // isi dropdown biaya
        data.forEach(function(biaya) {
            biayaDropdown.append(
                `<option value="${biaya.id_biaya}" data-biak='${JSON.stringify(biaya)}'>${biaya.nama_biaya}</option>`
            );
        });

        // listen perubahan pilihan biaya
        biayaDropdown.on("change", function() {
            let selectedId = $(this).val();
            let detailContainer = $("#biaya-detail");
            detailContainer.empty();

            if (!selectedId) return; // kalau belum pilih apa-apa

            // ambil data biaya dari attribute option
            let biaya = JSON.parse($(this).find(":selected").attr("data-biak"));

            let html = `<div class="biaya-item">`;

            //jika biaya periode
            if(biaya.periode){
                $('#periode-biaya').show();
            } else {
                $('#periode-biaya').hide();
            }

            //reset nominal jika sudah di isi
            $('#nominal').val(0);

            if (biaya.kelompok=='Perpetak') {
                // 🔹 Dropdown semua siklus
                html += `
                    <div class="form-group">
                        <label>Pilih Siklus</label>
                        <select id="siklus-perpetak" class="form-control">
                            <option value="">-- Pilih Siklus --</option>
                        </select>
                    </div>
                    <div class="form-group mt-3" id="petak-container" style="display:none;">
                        <label>Pilih Petak</label>
                        <select id="petak-perpetak" class="form-control">
                            <option value="">-- Pilih Petak --</option>
                        </select>
                    </div>
                `;

                // ambil semua siklus via ajax
                $.getJSON("/setup-siklus/all", function(res) {
                    let siklusSelect = $("#siklus-perpetak");
                    res.forEach(function(s) {
                        siklusSelect.append(`<option value="${s.id_siklus}">${s.nama_siklus} (${s.lokasi.nama_lokasi})</option>`);
                    });
                });

                // event listener pilih siklus
                $(document).off("change", "#siklus-perpetak").on("change", "#siklus-perpetak", function() {
                    let siklusId = $(this).val();
                    let petakSelect = $("#petak-perpetak");
                    let petakContainer = $("#petak-container");

                    petakSelect.empty().append(`<option value="">-- Pilih Petak --</option>`);
                    if (!siklusId) {
                        petakContainer.hide();
                        return;
                    }

                    // ambil petak berdasarkan siklus
                    $.getJSON("/setup-siklus/" + siklusId + "/petak", function(res) {
                        if (res.length > 0) {
                            res.forEach(function(p) {
                                petakSelect.append(`<option value="${p.id_petak}">${p.nama_petak}</option>`);
                            });
                            petakContainer.show();
                        } else {
                            petakContainer.hide();
                        }
                    });
                });
                
            } else {
                // kalau tidak ada petak_id → looping lokasi
                html +='</br>'
                biaya.lokasi.forEach(function(lokasi, i) {
                    html += `<div class="lokasi-group mb-3">`;
                    html += `<label>Pilih Siklus <b>${lokasi.nama_lokasi}</b></label>`;
                    html += `<select class="form-control siklus-dropdown" 
                                data-lokasi="${lokasi.id_lokasi}" 
                                id="siklus-dropdown-${i}">`;

                    if (lokasi.siklus.length > 0) {
                        html += `<option value="">-- Pilih Siklus --</option>`;
                        lokasi.siklus.forEach(function(siklus) {
                            html += `<option value="${siklus.id_siklus}">${siklus.nama_siklus}</option>`;
                        });
                    } else {
                        html += `<option value="">(Tidak ada siklus)</option>`;
                    }

                    html += `</select>`;
                    html += `</div>`;
                });
            }

            html += `</div>`;
            detailContainer.html(html);

        });
    });

    $(document).off("change", "#siklus-perpetak, #petak-perpetak")
    .on("change", "#siklus-perpetak, #petak-perpetak", function() {
        let siklus_id =$("#siklus-perpetak").val() || 0;
        let petak_id =$("#petak-perpetak").val() || 0;
        tablePetak.ajax.url("/transaksi-biaya/petak-list?siklus_id[]=" + [siklus_id] + "&petak_id=" + petak_id).load();
    });

    $.get('/setup-biaya/coa-list', function(res) {
        $('#coa_id').empty();
        res.forEach(function(coa) {
            $('#coa_id').append('<option value="'+coa.id_coa+'">'+coa.kode_coa+' - '+coa.nama_coa+'</option>');
        });
    });

    // event listener kalau dropdown siklus berubah
    $(document).on("change", "[id^='siklus-dropdown']", function() {
        reloadPetakTable();
    });

    // fungsi reload DataTable berdasarkan siklus terpilih
    function reloadPetakTable() {
        let selectedSiklus = [];

        // cari semua dropdown siklus yang ada
        $("[id^='siklus-dropdown']").each(function() {
            let val = $(this).val();
            if (val) {
                selectedSiklus.push(val);
            }
        });

        // reload DataTable dengan parameter siklus_id[]
        tablePetak.ajax.url("/transaksi-biaya/petak-list?siklus_id[]=" + selectedSiklus.join("&siklus_id[]=")).load();
    }

    // inisialisasi DataTable petak
    tablePetak = $('#viewtabelpetak').DataTable({
        paging: false,
        info: false,
        searching: false,
        processing: true,
        serverSide: false,
        ajax: {
            url: "/transaksi-biaya/petak-list",
            data: function(d) {
                // biar juga bisa handle via object
                let selectedSiklus = [];
                $("[id^='siklus-dropdown']").each(function() {
                    let val = $(this).val();
                    if (val) selectedSiklus.push(val);
                });
                d.siklus_id = selectedSiklus; // kirim array ke server
            }
        },
        columns: [
            { data: 'petak_id', name: 'petak_id', visible: false },
            { data: 'siklus_id', name: 'siklus_id', visible: false },
            { data: 'nama_lokasi', name: 'nama_lokasi' },
            { data: 'nama_petak', name: 'nama_petak' },
            { data: 'status_panen', name: 'status_panen' },
            { 
                data: 'luas', 
                name: 'luas',
                render: function(data) {
                    return parseInt(data).toLocaleString('id-ID');
                }
            },
            { 
                data: 'persentase', 
                name: 'persentase',
                render: function(data) {
                    return parseFloat(data).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' %';
                }
            },
            { 
                data: 'biaya_perpetak', 
                name: 'biaya_perpetak',
                render: function(data) {
                    return parseFloat(data).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }
            },
        ],
        footerCallback: function(row, data, start, end, display) {
            let api = this.api();

            // helper untuk hitung kolom
            let intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\.,]/g, '')*1 :
                    typeof i === 'number' ? i : 0;
            };

            // total luas
            let totalLuas = api.column(5).data().reduce((a, b) => intVal(a) + intVal(b), 0);

            // total persentase (harusnya ~100)
            let totalPersen = api.column(6).data().reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

            // total biaya
            let totalBiaya = api.column(7).data().reduce((a, b) => intVal(a) + intVal(b), 0);

            // Update footer
            $(api.column(4).footer()).html("<b>Total</b>");
            $(api.column(5).footer()).html("<b>" + totalLuas.toLocaleString('id-ID') + "</b>");
            $(api.column(6).footer()).html("<b>" + totalPersen.toFixed(2) + " %</b>");
            $(api.column(7).footer()).html("<b>" + totalBiaya.toLocaleString('id-ID') + "</b>");
        }
    });

    let debounceTimer;

    $(document).on("input", "#nominal", function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            hitungBiayaPerPetak();
        }, 500);
    });

    function hitungBiayaPerPetak() {
        let nominal = parseFloat($("#nominal").val()) || 0;

        // Ambil semua data petak dari DataTable
        let data = tablePetak.rows().data().toArray();
        console.log("Data Petak:", data);

        // Hitung total luas
        let totalLuas = data.reduce((sum, row) => sum + parseFloat(row.luas || 0), 0);
        console.log("Total Luas:", totalLuas);

        // Update data dengan persentase & biaya_perpetak
        data.forEach(function(row) {
            let luas = parseFloat(row.luas || 0);

            if (totalLuas > 0) {
                row.persentase = ((luas / totalLuas) * 100).toFixed(2); // %
                console.log("Luas:", luas, "Persentase:", row.persentase);
                row.biaya_perpetak = ((luas / totalLuas) * nominal).toFixed(0); // Rp
                console.log("Biaya per Petak:", row.biaya_perpetak);
            } else {
                row.persentase = 0;
                row.biaya_perpetak = 0;
            }
        });

        // Reload DataTable dengan data baru
        tablePetak.clear().rows.add(data).draw();
    }


    var table = $('#viewtabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/transaksi-biaya/data?type=validasi",
        columns: [
            { data: 'no_transaksi', name: 'no_transaksi' },
            { data: 'siklus', name: 'siklus' },
            { 
                data: 'tanggal_transaksi', 
                name: 'tanggal_transaksi',
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID');
                }
            },
            { data: 'biaya', name: 'biaya' },
            { 
                data: 'nominal', 
                name: 'nominal',
                render: function(data) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#btnTambah').click(function() {
        $('#formTransaksi')[0].reset();
        $('#uuid').val('');
        $('#m_create').modal('show');
    });

    $(document).on('click', '#btn-validasi', function() {
        let uuid= $("#uuid").val()
        Swal.fire({
            title: 'Validasi Transaksi',
            text: 'Yakin ingin validasi transaksi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, validasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                swal({title: "Processing...!",text: "Please Wait",
                    onOpen: function() {
                        swal.showLoading()
                    }
                })
                $.ajax({
                    url: '/transaksi-biaya/validasi/' + uuid,
                    method: 'POST',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(res) {
                        Swal.close();
                        if(res.success) {
                            Swal.fire('Berhasil', 'Data berhasil divalidasi!', 'success');
                            $('#viewtabel').DataTable().ajax.reload();
                            angular.element($('#viewtabel')).scope().$apply(function(scope){
                                scope.form = "list";
                            });
                        } else {
                            Swal.fire('Gagal', 'Data gagal divalidasi!', 'error');
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memvalidasi data!', 'error');
                    }
                });
            }
        });
    });

});

function validateTransaksi(uuid) {
    $.get('/transaksi-biaya/' + uuid, function(res) {
        $('#uuid').val(res.uuid);
        $('#no_transaksi').val(res.no_transaksi);
        $('#tanggal_transaksi').val(res.tanggal_transaksi).change();
        $('#tanggal_mulai').val(res.tanggal_mulai).change();
        $('#tanggal_selesai').val(res.tanggal_selesai).change();
        $('#keterangan').val(res.keterangan);
        $('#coa_id').val(res.coa_id);
        
        $('#biaya-dropdown').val(res.biaya_id).trigger("change");

        setTimeout(function () {
            // kelompok biaya dari relasi biaya
            let kelompok = res.biaya.kelompok;

            if (kelompok === 'Perpetak') {
                // Ambil hanya satu siklus (karena Perpetak 1 siklus 1 petak)
                let siklusId = res.siklus.length ? res.siklus[0].siklus_id : null;
                $("#siklus-perpetak").val(siklusId).trigger("change");

                setTimeout(function () {
                    // kalau ada petak → pilih salah satu
                    if (res.siklus[0] && res.siklus[0].petak.length > 0) {
                        console.log("Pilih petak:", res.siklus[0].petak[0].petak_id);
                        $("#petak-perpetak").val(res.siklus[0].petak[0].petak_id).trigger("change");

                        // reload table
                        if (siklusId) {
                            let petakId = (res.siklus[0].petak[0]) ? res.siklus[0].petak[0].petak_id : 0;
                            console.log("Reload table dengan siklus:", siklusId, "dan petak:", petakId);
                            tablePetak.ajax
                                .url(`/transaksi-biaya/petak-list?siklus_id[]=${siklusId}&petak_id=${petakId}`)
                                .load(function() {
                                    $('#nominal').val(parseFloat(res.nominal)).trigger("input");
                                });
                        }
                    }
                }, 500);

            } else {
                // Gabungan / lainnya → bisa banyak siklus
                let siklusIds = res.siklus.map(s => s.siklus_id);

                // isi dropdown siklus sesuai lokasi
                siklusIds.forEach(function (siklusId) {
                    $("[id^='siklus-dropdown']").each(function () {
                        if ($(this).find("option[value='" + siklusId + "']").length > 0) {
                            $(this).val(siklusId).trigger("change");
                        }
                    });
                });

                // reload DataTable
                if (siklusIds.length > 0) {
                    tablePetak.ajax
                        .url("/transaksi-biaya/petak-list?siklus_id[]=" + siklusIds.join("&siklus_id[]="))
                        .load(function() {
                            $('#nominal').val(parseFloat(res.nominal)).trigger("input");
                        });
                } else {
                    tablePetak.ajax.url("/transaksi-biaya/petak-list").load(function() {
                        $('#nominal').val(parseFloat(res.nominal)).trigger("input");
                    });
                }
            }
        }, 500);
        
        angular.element($('#viewtabel')).scope().$apply(function(scope){
            scope.form = "input";
        });
    });
}
</script>