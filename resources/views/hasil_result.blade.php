@extends('layouts.app')

@section('content')
    <style>
        .apexcharts-text tspan {
            font-size: 16px;
            font-family: inherit;
        }
    </style>
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow shadow-lg border-0 p-3">
                    <p class="fw-bold">Pilih Pimpinan</p>
                    <select class="form-control w-25" id="pimpinan" onchange="getData()">
                        <option value="PRM">PRM</option>
                        <option value="PRA">PRA</option>
                    </select>
                    <hr />
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function getData() {
            var pimpinan = $('#pimpinan').val();
            Swal.fire({
                title: null,
                text: 'Proses Menyiapkan Data',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            if (pimpinan) {
                var jumlah_vote = [];
                var namaPimpinan = [];
                $.ajax({
                    url: "/getVote?pimpinan=" + pimpinan,
                    method: "GET",
                    dataType: "JSON",
                    success: function(data, textStatus, jqXHR) {
                        // Iterate through the data array
                        $.each(data, function(idx, item) {
                            jumlah_vote.push(item.jumlah_vote); // Use push to add elements to the array
                            namaPimpinan.push(item.nama);
                        });
                        var options = {
                            series: [{
                                data: jumlah_vote
                            }],
                            chart: {
                                type: 'bar',
                                height: 750,
                                toolbar: {
                                    show: false, // Hide the toolbar to provide more space
                                },
                                animations: {
                                    enabled: false, // Disable animations for faster rendering
                                },
                                events: {
                                    mounted: function(chartContext, config) {
                                        chartContext.updateOptions({
                                            xaxis: {
                                                labels: {
                                                    style: {
                                                        fontSize: '14px',
                                                    },
                                                },
                                            },
                                        });
                                    },
                                },
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    horizontal: true,
                                },
                                dataLabels: {
                                    enabled: true,
                                    style: {
                                        fontSize: '14px',
                                    },
                                },
                            },
                            xaxis: {
                                categories: namaPimpinan,
                                labels: {
                                    style: {
                                        fontSize: '14px',
                                    },
                                },
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        fontSize: '14px',
                                    },
                                },
                            },
                            responsive: [{
                                breakpoint: 600,
                                options: {
                                    chart: {
                                        height: 500,
                                    },
                                },
                            }, ],
                        };

                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();


                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();

                        Swal.close();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.close();
                    }
                });
            }
        }

        $(document).ready(function() {
            getData()
        })
    </script>
@endpush
