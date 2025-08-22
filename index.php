<?php
// Untuk membaca file .env
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />

    <!-- Untuk mengatur scale web -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Untuk mengatur tulisan yang ada di tab browser -->
    <title>Wisata Kabupaten Kudus</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/feather/feather.css" />
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css" />
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
    <!-- endinject -->

    <!-- Plugin css for this page (untuk styling website) -->
    <link
        rel="stylesheet"
        href="vendors/datatables.net-bs4/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="js/select.dataTables.min.css" />

    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css" />
    <!-- endinject -->

    <!-- Untuk mengubah logo yang ada di tab browser -->
    <link rel="shortcut icon" href="images/logo-mini.png" />
</head>

<body>
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

            <!-- baris di bawah ini untuk mengganti LOGO navigasi yang ada di kiri atas web -->
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.php"><img src="images/logo.png" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.png" alt="logo" /></a>
            </div>

            <!-- kalau ingin menambah navigasi di web bagian atas, lakukan pengubahan di sini -->
            <div
                class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right"></ul>

                <!-- baris di bawah ini akan muncul jika website dibuka dari hp, yaitu berupa garis tiga di kanan atas -->
                <button
                    class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                    type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->

        <div class="container-fluid page-body-wrapper">

            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <!-- Baris kode di bawah ini untuk menampilkan tulisan Selamat Datang -->
                    <div class="row">

                        <!-- Delapan baris kode ini tidak boleh dipisah, harus selalu nyambung seperti di bawah. Kalau mau digeser, geser ketujuh baris sekaligus -->
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <!-- Baris kode di bawah ini untuk mengganti tulisan -->
                                    <h3 class="font-weight-bold">Selamat Datang di Wisata Kabupaten Kudus</h3>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <!-- Baris kode di bawah ini untuk menampilkan kotak untuk input pencarian. Kalau mau digeser sekalian sampai baris ke 135 -->
                        <div class="col grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <!-- yang di bawah ini untuk mengganti tulisan -->
                                    <h4 class="card-title">Cari Tempat Wisata di Kudus</h4>

                                    <!-- yang di bawah ini untuk menampilkan kotak input pencarian. Kalau mau digeser sekalian dari baris 115 sampai 121-->
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Ketik di sini untuk mencari..."
                                            id="search-input" />
                                    </div>

                                    <div class="form-group d-flex align-items-center">
                                        <label class="mr-3 mb-0">Jenis Wisata:</label>
                                        <div class="form-check form-check-inline mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="jenis_wisata" id="wisata_semua" value="semua" checked>
                                            <label class="form-check-label m-0" for="wisata_semua">Semua</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="jenis_wisata" id="wisata_alam" value="wisata alam">
                                            <label class="form-check-label m-0" for="wisata_alam">Alam</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="jenis_wisata" id="wisata_budaya" value="wisata budaya">
                                            <label class="form-check-label m-0" for="wisata_budaya">Budaya</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0 d-flex align-items-center">
                                            <input class="form-check-input" type="radio" name="jenis_wisata" id="wisata_kuliner" value="wisata kuliner">
                                            <label class="form-check-label m-0" for="wisata_kuliner">Kuliner</label>
                                        </div>
                                    </div>

                                    <!-- ini tombol Cari yang berwarna ungu -->
                                    <button class="btn btn-primary" id="search-button">
                                        Cari
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Baris kode di bawah ini untuk menampilkan hasil pencarian -->
                    <div class="row mb-4" id="search-results-header">
                        <div class="col">
                            <h3>Rekomendasi Wisata Di Dekat Anda</h3>
                        </div>
                    </div>

                    <!-- hasil hasil pencarian akan ditampilkan di sini -->
                    <div class="row" id="search-results">

                    </div>

                    <!-- Tombol untuk melihat lebih banyak hasil pencarian. Tombol ini akan hilang jika hasil pencariannya tidak lebih dari 6-->
                    <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-center mt-4">
                                <button class="btn btn-outline-primary" id="next-page-btn" style="display:none;">
                                    Lihat Lebih Banyak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->


                <!-- footer tidak usah dipindah-pindah -->
                <footer class="footer">
                    <div
                        class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span
                            class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium
                            <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a>
                            from BootstrapDash. All rights reserved.</span>
                        <span
                            class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with
                            <i class="ti-heart text-danger ml-1"></i></span>
                    </div>
                    <div
                        class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span
                            class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by
                            <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- ---------------------------------------------------------------- -->
    <!-- MULAI BARIS INI SAMPAI KE BAWAH HANYA NGEFEK SEDIKIT KE TAMPILAN -->
    <!-- ---------------------------------------------------------------- -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->


    <script>
        // Ambil elemen hasil pencarian
        const locationResult = document.getElementById('search-results');

        // Cek apakah Geolocation didukung oleh browser
        if ('geolocation' in navigator) {
            locationResult.innerHTML = 'Meminta lokasi...';

            // Panggil Geolocation API untuk mendapatkan lokasi pengguna
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            // Jika browser tidak mendukung Geolocation
            locationResult.innerHTML = 'Maaf, browser Anda tidak mendukung Geolocation.';
        }

        // Fungsi callback jika berhasil mendapatkan lokasi
        function successCallback(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Kirim permintaan AJAX ke backend untuk mencari tempat wisata terdekat
            $.ajax({
                url: 'proxy-nearby-search.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    includedTypes: [
                        "amusement_park",
                        "aquarium",
                        "cafe",
                        "garden",
                        "historical_landmark",
                        "hiking_area",
                        "internet_cafe",
                        "library",
                        "museum",
                        "national_park",
                        "opera_house",
                        "park",
                        "picnic_ground",
                        "plaza",
                        "planetarium",
                        "roller_coaster",
                        "tourist_attraction",
                        "zoo",
                    ],
                    maxResultCount: 6, // Batas jumlah hasil wisata terdekat yang ditampilkan. Jika ingin menampilkan lebih banyak/sedikit, ubah nilainya
                    locationRestriction: { // Batas lokasi pencarian. Disesuaikan dengan wilayah Kabupaten Kudus
                        circle: {
                            center: {
                                latitude: latitude,
                                longitude: longitude
                            },
                            radius: 500 // Radius pencarian dalam meter
                        }
                    }
                }),
                success: function(data) {
                    // Tampilkan hasil pencarian
                    renderResults(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Tampilkan pesan error jika gagal
                    console.error('Request failed:', textStatus, errorThrown);
                    locationResult.innerHTML = 'Terjadi kesalahan saat mengambil data lokasi.';
                }
            });

        }

        // Fungsi callback jika terjadi error saat mengambil lokasi
        function errorCallback(error) {
            let message = '';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    message = "Akses lokasi ditolak oleh pengguna.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    message = "Permintaan lokasi timeout.";
                    break;
                default:
                    message = "Terjadi error yang tidak diketahui.";
                    break;
            }
            locationResult.innerHTML = message;
        }
    </script>
    <script>
        // Jalankan kode setelah dokumen siap
        $(document).ready(function() {
            // Event saat tombol cari diklik
            $('#search-button').off('click').on('click', function() {
                doSearch();
                // Update judul hasil pencarian
                $('#search-results-header').empty();
                $('#search-results-header').append('<div class="col"><h3>Hasil Pencarian untuk: <span class="text-primary">' + $('#search-input').val() + '</span></h3></div>');
            });
            // Event saat tombol Enter ditekan di input pencarian
            $('#search-input').off('keydown').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#search-button').click();
                }
            });
            // Event untuk tombol "Lihat Lebih Banyak"
            $('#next-page-btn').on('click', function() {
                if (nextPageToken) {
                    doSearch(nextPageToken, true);
                }
            });
        });

        // Variabel global untuk menyimpan token halaman berikutnya dan data pencarian terakhir
        let nextPageToken = null;
        let lastSearchTerm = '';
        let lastRequestData = {};

        // Fungsi untuk menampilkan hasil pencarian ke halaman
        function renderResults(data, append = false) {
            if (!append) $('#search-results').empty();
            if (data.places && data.places.length > 0) {
                data.places.forEach(function(item) {
                    // Ambil URL foto jika tersedia, jika tidak gunakan gambar default
                    let photoUrl = item.photos ?
                        'ambil-foto.php?name=' + item.photos[0].name + '&maxWidthPx=470' :
                        'images/dashboard/people.svg';

                    // Tambahkan hasil ke elemen search-results
                    $('#search-results').append(
                        '<div class="col-md-4 stretch-card grid-margin">' +
                        '<a href="detail.php?place_id=' + item.id + '" class="text-decoration-none">' +
                        '<div class="card">' +
                        '<div class="card-body">' +
                        '<p class="card-title">' + item.displayName.text + '</p>' +
                        '<img src="' + photoUrl + '" alt="Tempat Wisata" class="img-fluid rounded mb-3" style="height:35vh; object-fit:cover; width:100%;" />' +
                        '<p class="text-black">' + (item.formattedAddress || 'Alamat tidak tersedia') + '</p>' +
                        '<p class="text-info"> <i class="mdi mdi-star"></i>  ' + (item.rating || '0') + '</p>' +
                        '<small class="text-black"> <i class="mdi mdi-google-maps"></i> Google Maps: <a href="' + item.googleMapsUri + '" target="_blank">Link</a></small>' +
                        '</div>' +
                        '</div>' +
                        '</a>' +
                        '</div>'
                    );
                });
            } else if (!append) {
                // Jika tidak ada hasil, tampilkan pesan
                $('#search-results').append('<li class="list-group-item">Tidak ada hasil yang ditemukan.</li>');
            }
        }

        // Fungsi untuk melakukan pencarian berdasarkan input pengguna
        function doSearch(pageToken = null, append = false) {
            // menangkap input kalimas yang ditulis dari pengguna, disimpan dalam variabel searchTerm
            var searchTerm = $('#search-input').val().toLowerCase();
            if (!searchTerm) {
                // Jika input kosong, tampilkan pesan
                $('#search-results').empty().append('<li class="list-group-item">Tolong masukkan kode pencarian.</li>');
                $('#next-page-btn').hide();
                return;
            }
            var jenisWisata = $('input[name="jenis_wisata"]:checked').val();
            if (jenisWisata !== 'semua') {
                // Jika jenis wisata tidak "semua", tambahkan filter ke searchTerm
                searchTerm = jenisWisata + ' ' + searchTerm;
                console.log('Jenis wisata yang dipilih:', searchTerm);
            }
            lastSearchTerm = searchTerm;
            // Siapkan data request untuk dikirim ke backend
            let requestData = {
                textQuery: searchTerm + "<?php echo $_ENV['LOCATION'] ?>", // Tambahkan lokasi Kabupaten Kudus ke query
                maxResultCount: 6, // Batas jumlah hasil wisata relevan yang ditampilkan. Jika ingin menampilkan lebih banyak/sedikit, ubah nilainya
                locationRestriction: {
                    rectangle: {
                        low: {
                            latitude: <?php echo $_ENV['LOW_LATITUDE'] ?>,
                            longitude: <?php echo $_ENV['LOW_LONGITUDE'] ?>
                        },
                        high: {
                            latitude: <?php echo $_ENV['HIGH_LATITUDE'] ?>,
                            longitude: <?php echo $_ENV['HIGH_LONGITUDE'] ?>
                        }
                    }
                }
            };
            // Jika ada token halaman sebelumnya, tambahkan ke requestData. Ini menandakan bahwa hasil pencarian ada banyak. Sebelumnya kita ambil 6 dulu
            if (pageToken) requestData.pageToken = pageToken;
            lastRequestData = requestData;
            // Kirim permintaan AJAX ke backend
            $.ajax({
                url: 'proxy.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(data) {
                    // Tampilkan hasil pencarian
                    renderResults(data, append);
                    // Simpan token untuk halaman berikutnya jika ada
                    nextPageToken = data.nextPageToken || null;
                    if (nextPageToken) {
                        $('#next-page-btn').show();
                    } else {
                        $('#next-page-btn').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Tampilkan pesan error jika gagal
                    console.error('Request failed:', textStatus, errorThrown);
                    $('#next-page-btn').hide();
                }
            });
        }

        // Animasi hover pada kartu hasil pencarian agar lebih interaktif
        $('#search-results').on('mouseenter', '.card', function() {
            $(this).css({
                'transition': 'box-shadow 0.2s, transform 0.2s',
                'box-shadow': '0 8px 24px rgba(0,0,0,0.15)',
                'transform': 'scale(1.03)'
            });
        }).on('mouseleave', '.card', function() {
            $(this).css({
                'transition': 'box-shadow 0.2s, transform 0.2s',
                'box-shadow': '0 2px 8px rgba(0,0,0,0.05)',
                'transform': 'scale(1)'
            });
        });
    </script>
</body>

</html>