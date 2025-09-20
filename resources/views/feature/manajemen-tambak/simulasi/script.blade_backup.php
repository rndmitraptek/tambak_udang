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
    $scope.simulasi = {
        siklus: "",
        tanggal_simulasi: "",
        catatan: ""
    };
    $scope.list_simulasi = [];
    $scope.detail = null;
    $scope.tambah_simulasi = function(){
        $scope.simulasi = {
            siklus: "",
            tanggal_simulasi: "",
            catatan: ""
        };
        $('#m_create_simulasi').modal('show');
    }
    $scope.add_simulasi = function(){
        $scope.simulasi.kolam = $scope.kolam;
        $scope.list_simulasi.push($scope.simulasi);
        $('#m_create_simulasi').modal('hide');
        console.log($scope.list_simulasi);
    }
    $scope.get_detail = function(item,index){
        $scope.detail = angular.copy(item);
        $scope.selected_index = index;
        console.log($scope.detail);
    }
    $scope.save_pendapatan = function(){
        $scope.detail.kolam.forEach(function(item, index) {
            item.laba = item.pendapatan - item.biaya;
            item.hpp_per_kg = item.biaya / item.biomassa;
        });
        $scope.list_simulasi[$scope.selected_index] = angular.copy($scope.detail);
    }
    $scope.add_biaya = function(){
        $('#m_create_biaya').modal('show');
    }
});

</script>