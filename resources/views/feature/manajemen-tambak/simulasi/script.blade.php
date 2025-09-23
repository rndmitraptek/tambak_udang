<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#catatan"));
        $("#viewtabel").DataTable({
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columnDefs: [{
                targets: -1,
                title: "Actions",
                orderable: !1,
                render: function(a, e, t, n) {
                    return `<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove"></i></a>`
                }
            }],
        })
        $("#viewtabelactual").DataTable({
            scrollY: "50vh",
            scrollX: !0,
            scrollCollapse: !0,
            columnDefs: [{
                targets: -1,
                title: "Actions",
                orderable: !1,
                render: function(a, e, t, n) {
                    return `<a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit"></i></a>
                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove"></i></a>`
                }
            }],
        })
        
    });
    $scope.tes = "tes";
    $scope.lokasi = "";
    $scope.selected_index = 0;
    $scope.kolam = [
        {
            "checked": true,
            "area": "A1",
            "nama_kolam": "Kolam 001",
            "luas": 1000,
            "keterangan": "keterangan kolam 001 luas 1.000 meter persegi",
            "pendapatan": 0,
            "pendapatan_partial": 2000000,
            "biaya": 2000000,
            "laba": -2000000,
            "biomassa": 0,
            "harga_per_kg": 0,
            "hpp_per_kg": 0,
        },
        {
            "checked": true,
            "area": "A1",
            "nama_kolam": "Kolam 002",
            "luas": 2000,
            "keterangan": "keterangan kolam 002 luas 2.000 meter persegi",
            "pendapatan": 0,
            "pendapatan_partial": 2000000,
            "biaya": 4000000,
            "laba": -4000000,
            "biomassa": 0,
            "harga_per_kg": 0,
            "hpp_per_kg": 0,
        },
        {
            "checked": true,
            "area": "A2",
            "nama_kolam": "Kolam 003",
            "luas": 500,
            "keterangan": "keterangan kolam 002 luas 2.000 meter persegi",
            "pendapatan": 0,
            "pendapatan_partial": 2000000,
            "biaya": 1000000,
            "laba": -1000000,
            "biomassa": 0,
            "harga_per_kg": 0,
            "hpp_per_kg": 0,
        }
    ];

    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = ('0' + (today.getMonth() + 1)).slice(-2);
    var dd = ('0' + today.getDate()).slice(-2);

    // init datepicker after DOM ready
    angular.element(document).ready(function () {
        $('#tanggal_simulasi').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(e) {
            $scope.$apply(function() {
                $scope.simulasi.tanggal_simulasi = $('#tanggal_simulasi').val();
            });
        });
    });
    $scope.simulasi = {
        siklus: "",
        tanggal_simulasi: yyyy + '-' + mm + '-' + dd,
        catatan: ""
    };
    $scope.list_simulasi = [];
    $scope.total_records = 0;
    $scope.page = 1;
    $scope.per_page = 5;
    $scope.total_page = 0;

    $scope.loadSimulasi = function(page) {
        var start = (page - 1) * $scope.per_page;

        swal({title: "Processing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get('/simulasi/data', {
            params: {
                draw: 1,         // DataTables param
                start: start,
                length: $scope.per_page
            }
        }).then(function(res){
            $scope.list_simulasi = res.data.data; // isi tabel
            $scope.total_records = res.data.recordsTotal; // total semua record
            $scope.total_page = Math.ceil($scope.total_records/$scope.per_page);
            Swal.close();
        }, function(err){
            Swal.close();
            console.error(err);
        });
    };

    $scope.loadSimulasi($scope.page);

    // untuk berpindah halaman:
    $scope.nextPage = function() {
        if ($scope.page * $scope.per_page < $scope.total_records) {
            $scope.page++;
            $scope.loadSimulasi($scope.page);
        }
    };

    $scope.prevPage = function() {
        if ($scope.page > 1) {
            $scope.page--;
            $scope.loadSimulasi($scope.page);
        }
    };


    $scope.detail = null;
    $scope.judul = null;
    $scope.tambah_simulasi = function(){
        $scope.simulasi = {
            siklus: "",
            tanggal_simulasi: yyyy + '-' + mm + '-' + dd,
            catatan: ""
        };
        $('#m_create_simulasi').modal('show');
    }

    // $scope.add_simulasi = function(){
    //     $scope.simulasi.kolam = $scope.petakList;
    //     // $scope.list_simulasi.push($scope.simulasi);
    //     $('#m_create_simulasi').modal('hide');
    //     // console.log($scope.list_simulasi);
    // }
    // $scope.get_detail = function(item,index){
    //     $scope.detail = angular.copy(item);
    //     $scope.selected_index = index;
    //     console.log($scope.detail);
    // }
    // $scope.save_pendapatan = function(){
    //     $scope.detail.kolam.forEach(function(item, index) {
    //         item.laba = item.pendapatan - item.biaya;
    //         item.hpp_per_kg = item.biaya / item.biomassa;
    //     });
    //     $scope.list_simulasi[$scope.selected_index] = angular.copy($scope.detail);
    // }
    $scope.add_biaya = function(){
        $('#m_create_biaya').modal('show');
    }


    // load lokasi pertama kali
    $http.get('/simulasi/lokasi').then(function(res){
        $scope.lokasiList = res.data;
    });

    // watch lokasi: tiap kali berubah, ambil siklus
    $scope.$watch('simulasi.lokasi', function(newVal) {
        if (newVal) {
            $http.get('/simulasi/siklus/' + newVal).then(function(res){
                $scope.siklusList = res.data;
            });
        } else {
            $scope.siklusList = []; // kosongkan jika belum pilih lokasi
        }
    });

    // jika siklus berubah → ambil petak
    $scope.$watch('simulasi.siklus', function(newVal) {
        if (newVal && $scope.simulasi.lokasi && $scope.simulasi.tanggal_simulasi) {
            $scope.getPetakData(newVal,$scope.simulasi.tanggal_simulasi);
        } else {
            $scope.petakList = [];
        }
    });
    // tambahkan watcher untuk tanggal_simulasi
    $scope.$watch('simulasi.tanggal_simulasi', function (newVal) {
        if (newVal && $scope.simulasi.siklus && $scope.simulasi.lokasi) {
            $scope.getPetakData($scope.simulasi.siklus, newVal);
        } else {
            $scope.petakList = [];
        }
    });

    // fungsi ambil data petak
    $scope.petakList = []
    $scope.getPetakData = function(siklusId,tanggal_simulasi){
        swal({title: "Processing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get('/simulasi/petak/' + siklusId+'/'+tanggal_simulasi)
            .then(function (res) {
                Swal.close();
                $scope.petakList = res.data; // tampilkan sesuai kebutuhan
            })
            .catch(function (err) {
                Swal.close();
                // tampilkan pesan error swal
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan mengambil data petak. Silakan coba lagi.'
                });
                console.error('Error getPetakData:', err);
                // kosongkan list bila perlu
                $scope.petakList = [];
            });
    };

    $scope.totalBiayaSimulasi = function() {
        return $scope.petakList.reduce(function(total, item){
            return total + (item.biaya_simulasi || 0);
        }, 0);
    };

    $scope.selectedDetailBiaya = [];

    $scope.viewDetail = function(detailBiaya) {
        $scope.selectedDetailBiaya = detailBiaya; // JSON array detail
        $('#modalDetailBiaya').modal('show');
    };

    $scope.totalBiayaDetail = function() {
        return $scope.selectedDetailBiaya.reduce(function(total, b){
            return total + (b.biaya_hitung || 0);
        }, 0);
    };
    // agar scroll modal tidak hilang
    $(document).on('hidden.bs.modal', '.modal', function () {
    if($('.modal.show').length > 0) {
        $('body').addClass('modal-open'); // masih ada modal lain
    } else {
        $('body').removeClass('modal-open');
        $('body').css({overflow:'auto',paddingRight:''});
    }
    });

    // simpan simulasi
    $scope.add_simulasi = function () {
        let data = {
            siklus_id: $scope.simulasi.siklus,
            lokasi_id: $scope.simulasi.lokasi,
            tanggal_simulasi: $scope.simulasi.tanggal_simulasi,
            catatan: $scope.simulasi.catatan,
            petakList: $scope.petakList // sudah ada detail_biaya
        };

        swal({title: "Processing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.post('/simulasi/store', data).then(function (res) {
            Swal.close();
            swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: res.data.message
            });
            $('#m_create_simulasi').modal('hide');
            $scope.loadSimulasi();
        }).catch(function (err) {
            Swal.close();
            swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: err.data.message || 'Terjadi kesalahan'
            });
            console.error(err);
        });
    };


    //get detail
    $scope.get_detail = function(item,index){
        $scope.detail = angular.copy(item);
        $scope.judul = angular.copy(item);
        $scope.selected_index = index;
        console.log($scope.judul);

        swal({title: "Processing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        $http.get('/simulasi/show/' + $scope.detail.uuid)
            .then(function (res) {
                Swal.close();
                $scope.detail = res.data;
                $scope.simulasi.id_simulasi =res.data.id_simulasi;
            })
            .catch(function (err) {
                Swal.close();
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan mengambil data simulasi. Silakan coba lagi.'
                });
                console.error('Error getPetakData:', err);
                $scope.detail = angular.copy(item);
            });
    }


    //save pendapatan
    // $scope.save_pendapatan = function() {
    //     // data pendapatan bisa dari simulasi atau pendapatan tergantung kondisi
    //     let payload = ($scope.detail.pendapatan && $scope.detail.pendapatan.length > 0)
    //                     ? $scope.detail.pendapatan
    //                     : $scope.detail.simulasi;
    //     console.log($scope.simulasi.id_simulasi);
    //     console.log(payload);
    //     // kirim data ke backend
    //     swal({title: "Processing...!",text: "Please Wait",
    //         onOpen: function() {
    //             swal.showLoading()
    //         }
    //     })
    //     $http.post('/simulasi/pendapatan/save', {
    //         trans_simulasi_id: $scope.simulasi.id_simulasi, // atau uuid yg dipakai
    //         items: payload
    //     }).then(function(res){
    //         swal("Success!", "Data pendapatan berhasil disimpan!", "success");

    //         // panggil lagi get_detail untuk refresh
    //         // gunakan data yg sedang aktif (judul atau detail)
    //         if ($scope.detail && $scope.detail.uuid) {
    //             $scope.get_detail($scope.detail, $scope.selected_index);
    //         }
    //     }).catch(function(err){
    //         swal("Error!", "Gagal menyimpan pendapatan!", "error");
    //     });
    // };
    
});

</script>