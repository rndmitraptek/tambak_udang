<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        autosize($("#alamat"));
        setTimeout(function(){
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
        }, 500);
    });
    $scope.tes = "tes";
    $scope.form = "list";
    $scope.tambah = function(){
        $scope.form = "input";
    }
    $scope.kembali = function(){
        $scope.form = "list";
    }
    $scope.cari_supplier = function(){
        $('#m_supplier').modal('show');
    }
});
</script>