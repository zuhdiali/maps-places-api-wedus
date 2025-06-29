<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Wisata Kabupaten Kudus</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/feather/feather.css" />
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css" />
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
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
    <link rel="shortcut icon" href="images/logo-mini.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div
                class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.php"><img src="images/logo.png" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.png" alt="logo" /></a>
            </div>
            <div
                class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right"></ul>
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
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Selamat Datang di WEDUS (Wisata Kabupaten Kudus)</h3>
                                    <!-- <h6 class="font-weight-normal mb-0">
                      Cari informasi tempat wisata di
                      <span class="text-primary">Kudus!</span>
                    </h6> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Cari Tempat Wisata di Kudus</h4>
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Ketik di sini untuk mencari..."
                                            id="search-input" />
                                    </div>
                                    <button class="btn btn-primary" id="search-button">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4" id="search-results-header">
                        <div class="col">
                            <h3>Rekomendasi Wisata Di Dekat Anda</h3>
                        </div>
                    </div>
                    <div class="row" id="search-results">

                    </div>
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
                <!-- partial:partials/_footer.html -->
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
        const locationResult = document.getElementById('search-results');

        // Cek apakah Geolocation didukung oleh browser
        if ('geolocation' in navigator) {
            locationResult.innerHTML = 'Meminta lokasi...';

            // Panggil Geolocation API
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            locationResult.innerHTML = 'Maaf, browser Anda tidak mendukung Geolocation.';
        }

        // Fungsi callback jika berhasil mendapatkan lokasi
        function successCallback(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

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
                    maxResultCount: 6,
                    locationRestriction: {
                        circle: {
                            center: {
                                latitude: latitude,
                                longitude: longitude
                            },
                            radius: 500
                        }

                    }
                }),
                success: function(data) {
                    renderResults(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Request failed:', textStatus, errorThrown);
                    locationResult.innerHTML = 'Terjadi kesalahan saat mengambil data lokasi.';
                }
            });

        }

        // Fungsi callback jika terjadi error
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
        $(document).ready(function() {
            $('#search-button').off('click').on('click', function() {
                doSearch();
                $('#search-results-header').empty();
                $('#search-results-header').append('<div class="col"><h3>Hasil Pencarian untuk: <span class="text-primary">' + $('#search-input').val() + '</span></h3></div>');
            });
            $('#search-input').off('keydown').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#search-button').click();
                }
            });
            $('#next-page-btn').on('click', function() {
                if (nextPageToken) {
                    doSearch(nextPageToken, true);
                }
            });
        });

        let nextPageToken = null;
        let lastSearchTerm = '';
        let lastRequestData = {};

        function renderResults(data, append = false) {
            if (!append) $('#search-results').empty();
            if (data.places && data.places.length > 0) {
                data.places.forEach(function(item) {
                    let photoUrl = item.photos ?
                        'ambil-foto.php?name=' + item.photos[0].name + '&maxWidthPx=470' :
                        'images/dashboard/people.svg';

                    $('#search-results').append(
                        '<div class="col-md-4 stretch-card grid-margin">' +
                        '<a href="detail.php?place_id=' + item.id + '" class="text-decoration-none">' +
                        '<div class="card">' +
                        '<div class="card-body">' +
                        '<p class="card-title">' + item.displayName.text + '</p>' +
                        '<img src="' + photoUrl + '" alt="Tempat Wisata" class="img-fluid rounded mb-3" style="max-height:40vh; object-fit:cover; width:100%;" />' +
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
                $('#search-results').append('<li class="list-group-item">Tidak ada hasil yang ditemukan.</li>');
            }
        }

        function doSearch(pageToken = null, append = false) {
            var searchTerm = $('#search-input').val().toLowerCase();
            if (!searchTerm) {
                $('#search-results').empty().append('<li class="list-group-item">Tolong masukkan kode pencarian.</li>');
                $('#next-page-btn').hide();
                return;
            }
            lastSearchTerm = searchTerm;
            let requestData = {
                textQuery: searchTerm + "<?php echo $_ENV['LOCATION'] ?>",
                maxResultCount: 6,
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
            if (pageToken) requestData.pageToken = pageToken;
            lastRequestData = requestData;
            $.ajax({
                url: 'proxy.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(data) {
                    renderResults(data, append);
                    nextPageToken = data.nextPageToken || null;
                    if (nextPageToken) {
                        $('#next-page-btn').show();
                    } else {
                        $('#next-page-btn').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Request failed:', textStatus, errorThrown);
                    $('#next-page-btn').hide();
                }
            });
        }


        // Use CSS transitions for smoother animation
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