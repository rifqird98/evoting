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
                <div class="card shadow shadow-lg border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3">

                                <p class="font-weight-bolder">Pilih Pimpinan</p>
                                <select class="form-control input-produk  mb-3" onchange="getData()" id="filter_pimpinan">
                                    <option value="PRM">PRM</option>
                                    <option value="PRA">PRA</option>
                                </select>

                            </div>
                            <div class="col-xl-6 text-start">

                                <p class="font-weight-bolder">Pilih Status Peserta</p>
                                <select class="form-control input-produk w-50 mb-3" onchange="getData()" id="filter_status">
                                    <option value="false">Belum Memilih</option>
                                    <option value="proses">Proses Memilih</option>
                                    <option value="true">Sudah Memilih</option>
                                </select>

                            </div>
                            <div class="col-xl-3">
                                <button class="btn btn-success mt-4" onclick="viewVoting()"><i class="fa-solid fa-eye"></i>
                                    Lihat Hasil Voting</button>

                            </div>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-striped display table-bordered" id="dataPeserta" style="width: 100%;">
                                <thead class="font-weight-bold text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nama Pimpinan</th>
                                        <th>No NBM</th>
                                        <th>Status</th>
                                        <th>Pilih Perangkat</th>
                                        <th>Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-password">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="font-weight-bolder"> Password Voting</h5>
                </div>
                <div class="modal-body">

                    <form id="formPassword">
                        <div class="text-left mb-3">
                            <p class="fw-bold mb-2">Masukkan Password</p>
                            <input type="password" class="form-control" id="password">
                        </div>
                    </form>

                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <button type="button" class="btn btn-success" onclick="simpan()">Submit</button>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-content">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="font-weight-bolder">Hasil Voting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow shadow-lg border-0 p-3">
                        <p class="fw-bold">Pilih Pimpinan</p>
                        <select class="form-control w-25" id="pimpinan" onchange="getDataPimpinan()">
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@push('addon-script')
    <script>
        function getData() {
            var namaPimpinan = $("#filter_pimpinan").val();
            var statusPeserta = $("#filter_status").val();

            var tbl = $("#dataPeserta tbody");
            tbl.html('');
            Swal.fire({
                title: null,
                text: 'Proses Menyiapkan Data',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: "/getPeserta?namaPimpinan=" + namaPimpinan + "&statusPeserta=" + statusPeserta,
                method: "GET",
                dataType: "JSON",
                success: function(data, textStatus, jqXHR) {

                    if (data.length > 0) {
                        var no = 1;
                        var tr = "";
                        var status;
                        var perangkat;
                        $.each(data, function(idx, item) {
                            if (item.status_vote == 'false') {
                                status = "<button class='btn btn-danger'>Belum Memilih</button>"
                            } else if (item.status_vote == 'true') {
                                status = "<button class='btn btn-success'>Sudah Menilih</button>"

                            } else {

                                status = "<button class='btn btn-primary'>Proses</button>"
                            }
                            if (item.status_vote == 'false') {
                                var btn =
                                    "<a class='btn btn-outline-success mr-2' onclick='changeStatus(`" +
                                item
                                .id + "`)'>Tambah Giliran</a>"
                            } else {
                                var btn = `<p class='fw-bold'>#</p>`

                            }
                            if (item.status_vote == 'false') {
                                perangkat = `<select class='form-control' id='namaPerangkat'>
                                    <option value='Firefox'>Kumputer 1</option>
                                    <option value='Chrome'>Kumputer 2</option>
                                    </select>`
                            } else {
                                perangkat =
                                    `<p class='fw-bold'>${item.perangkat == 'Firefox' ? 'Komputer 1' : 'Komputer 2'}</p>`
                            }
                            tr = tr + "<tr>" +
                                "<td>" + no + "</td>" +
                                "<td>" + item.nama + "</td>" +
                                "<td>" + item.pimpinan + "</td>" +
                                "<td>" + item.nba + "</td>" +
                                "<td>" + status + "</td>" +
                                "<td>" + perangkat + "</td>" +
                                "<td>" +
                                btn +
                                "</td>" +
                                "</tr>";

                            no = no + 1;
                        })
                        tbl.append(tr);
                        $('#dataPeserta').dataTable()

                    }
                    Swal.close();

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                }
            });
        }

        function changeStatus(id) {
            const url = '/updateStatus';
            var perangkat = $('#namaPerangkat').val()
            // Data to be sent with the request
            const data = {
                id: id,
                perangkat: perangkat,
            };

            const options = {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(data),
            };

            // Make the AJAX request
            fetch(url, options)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(responseData => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Peserta Berhasil Masuk dalam Antrian',
                        icon: 'success',
                    }).then(() => {
                        // Call the getData function after the SweetAlert success message is closed
                        getData();
                    });

                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }

        function viewVoting() {
            $('#modal-password').modal("show");
        }

        function simpan() {
            var password = $('#password').val();
            if (password == "PRM2023") {
                $('#modal-password').hide()
                $('#modal-content').modal("show");
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Password Salah',
                    icon: 'error',
                })
            }
        }

        $(document).ready(function() {
            getData()
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function getDataPimpinan() {
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
            getDataPimpinan()
        })
    </script>
@endpush
