<script>
app.controller("myCtrl", function($scope,$http) {
    angular.element(document).ready(function () {
        $scope.get_siklus_petak();
    });

    $scope.get_siklus_petak = function(){
        swal({title: "Presesing...!",text: "Please Wait",
            onOpen: function() {
                swal.showLoading()
            }
        })
        url = "{{ route('dashboard.siklus_petak',':uuid_siklus') }}";
        url = url.replace(':uuid_siklus','{{ $uuid_siklus }}');
        $http.get(url).then(function(res){
            $scope.siklus = res.data.data;
            
            setTimeout(function() {
                $scope.siklus.forEach(function(item, index, array) {
                    item.data.forEach(function(item_data) {
                        item_data.pendapatan = parseFloat(item_data.pendapatan);
                        item_data.biaya = parseFloat(item_data.biaya);
                        item_data.y = parseFloat(item_data.y);
                    });
                    console.log(item.data);
                    new Chart($('#myChart_'+item.petak_id), {
                        type: 'line',
                        data: {
                        datasets: [{
                                label: 'Laba',
                                data:item.data,
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1,
                                segment: {
                                    borderColor: ctx => {
                                        const curr = ctx.p1.parsed.y;
                                        const prev = ctx.p0.parsed.y;
                                        return curr < prev ? 'red' : 'rgb(75, 192, 192)';
                                    }
                                },
                            }]
                        },
                        options: {
                        plugins: {
                        tooltip: {
                            callbacks: {
                                // Teks utama tooltip
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.parsed.y || 0;
                                    return `${label}: ${value.toLocaleString('id-ID')}`;
                                },
                                // Judul tooltip (bisa ubah label x)
                                title: function(context) {
                                    return 'Simulasi: ' + context[0].label;
                                },
                                afterLabel: function(context) {
                                    const point = context.raw;
                                    return [
                                        `Pendapatan: ${point.pendapatan.toLocaleString('id-ID')}`,
                                        `Biaya: ${point.biaya.toLocaleString('id-ID')}`,
                                    ];
                                }
                            }
                        }
                        }
                    }
                    });
                });
            },0);
            swal.close();
        }).catch(function(error) {
            console.log(error);
            swal({title: error.statusText,text: error.data.message,type: "error",confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"})
        });
    }
});

</script>