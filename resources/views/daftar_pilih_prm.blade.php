<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Voting PRM Sepanjang</title>

    <!-- Fonts -->
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables CSS -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/') }}css/dataTables.min.css"> --}}

    <!-- DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Styles -->

</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f1fbf1
    }

    .img-fluid {
        width: 40%;
        height: auto;
        border: 8px solid #ebebeb;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.367);
    }

    .active {
        border: 8px solid #0830b3;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.367);
        /* Ganti dengan gaya CSS yang sesuai dengan kebutuhan Anda */
    }

    .checkmark {
        position: absolute;
        top: 5px;
        right: 5px;
        color: green;
        font-size: 24px;
        display: none;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row mt-5 mb-5">
            <div class="card border border-0 bg-success">
                <div class="card-body">
                    <div class="col-xl-12 text-center" id="namePemilih">
        
                    </div>

                </div>
            </div>
        </div>
        <div class="card border border-0 shadow shadow-lg bg-success">
            <div class="card-body">
                <p class="fs-5 fw-bold text-center text-light">Berikut Adalah Calon Tetap Pimpinan Ranting Muhammadiyah Sepanjang :
                </p>
                <p class="text-center fw-bold text-light mb-3">Pilih Calon Pimpinan 7 orang tidak boleh lebih atau
                    kurang</p>
                <hr />
                <div class="row text-center" id="dataCalon" style="height: 550px; overflow-y: auto;">
                    <!-- Your content goes here -->
                </div>
            </div>
        </div>
        <hr />
    </div>
</body>
<script>
    function toggleActive(element) {
        element.classList.toggle('active');
    }
    var dataPemilih = [];
    var dataCalon = [];
    var namaPerangkat = ''
    var clickedIds = []

    function toggleActive(itemId) {
        var element = document.getElementById("yourElementId-" + itemId); // Replace with your actual element ID

        var index = clickedIds.findIndex(item => item.id_calon === itemId);
        var data = {
            'id_pemilih': dataPemilih.id,
            'id_calon': itemId,
        }
        if (index === -1) {
            clickedIds.push(data);
            element.classList.add('active');
            // element.querySelector('.checkmark').style.display = 'block';
            console.log(clickedIds);
            if (clickedIds.length >= 7) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Anda Sudah Memilih 7 Calon Pimpinan, Apakah Anda Akan Menyimpan Pilihan Anda ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: null,
                            text: 'Proses Menyimpan Data',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "/simpanSuara",
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            // Menggunakan JSON.stringify untuk mengubah objek menjadi string JSON
                            data: JSON.stringify(clickedIds),
                            dataType: 'json', // Menentukan tipe data yang diharapkan dari respons server
                            success: function(response) {
                                if (response) {
                                    Swal.fire(
                                        'Simpan Pilihan Berhasil',
                                        'Terima Kasih Atas Partisipasi Anda',
                                        'success'
                                    ).then(function() {
                                        location.reload();
                                    })
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // Tangani kesalahan di sini
                                console.error('Error:', errorThrown);
                            }
                        });
                    }
                })
            }
        } else {
            clickedIds.splice(index, 1);
            element.classList.remove('active');
            // element.querySelector('.checkmark').style.display = 'none';
            console.log(clickedIds)
        }
    }


    function getDataCalon() {

        var tbl = $("#dataCalon");
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
            url: "/getDaftarCalon?pimpinan=PRM",
            method: "GET",
            dataType: "JSON",
            success: function(data, textStatus, jqXHR) {
                var content = ""
                if (data.length > 0) {
                    $.each(data, function(idx, item) {
                        content = `<div class="col-xl-3 mb-3 clickable-div" onclick="toggleActive(${item.id})">
    <img class="img-fluid"  src="{{ asset('image/foto/${item.foto}') }}" alt="" id="yourElementId-${item.id}">
    <p class="fw-bold fs-5 mt-3 text-light">${item.nama}</p>
    <i class="checkmark fas fa-check-circle"></i>
</div>`;

                        tbl.append(content);
                    })

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        });
    }

    function getDataPemilih() {
        var tbl = $("#namePemilih");
        tbl.html('');
        $.ajax({
            url: "/getPemilihPmr?namaPerangkat=" + namaPerangkat,
            method: "GET",
            dataType: "JSON",
            success: function(data, textStatus, jqXHR) {
                if (data.nama) {
                    dataPemilih = data
                    var content = ""
                    content = ` <h3 class="fw-bold">Selamat Datang Bapak, <span class="text-light">${data.nama}</span></h3>
                    <h3 class="fw-bold">Di Sistem Pemilihan Pimpiman Ranting Muhammadiyah Sepanjang</h3>`

                    tbl.append(content);

                    Swal.close();
                } else {
                    // Set sisa waktu awal
                    let remainingTime = 30;

                    // Menampilkan SweetAlert dengan countdown
                    const countdownInterval = setInterval(function() {
                        remainingTime--;

                        Swal.fire({
                            title: 'Sedang Mencari Antrian Selanjutnya',
                            text: 'Tunggu selama ' + remainingTime + ' detik',
                            showConfirmButton: false,
                            onClose: function() {
                                location.reload()
                            }
                        });

                        // Jika waktu sudah habis, hentikan interval dan reload halaman
                        if (remainingTime <= 0) {
                            clearInterval(countdownInterval);
                            location.reload();
                        }
                    }, 1000); // Update setiap 1 detik
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        });
    }

    $(document).ready(function() {
        getDataCalon()
        getDataPemilih()
    })
    var userAgent = navigator.userAgent;

    if (userAgent.indexOf("Firefox") !== -1) {
        namaPerangkat = "Firefox"
    } else if (userAgent.indexOf("Chrome") !== -1) {
        namaPerangkat = "Chrome"
    } else {
        console.log("Peramban tidak dikenali");
    }
</script>

</html>
