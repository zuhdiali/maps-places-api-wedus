<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Sekarang Anda bisa mengakses API Key
$apiKey = $_ENV['GOOGLE_PLACES_API_KEY'];
$response_body = null;
$var = $_GET['place_id'] ?? 'default_value';
if ($var === 'default_value') {
    echo "Halaman tidak ditemukan. Silahkan kembali ke halaman sebelumnya.";
} else {
    // Ambil detail tempat dari Places API
    $detail_url = 'https://places.googleapis.com/v1/places/' . $var . '?languageCode=id';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $detail_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Goog-Api-Key: ' . $apiKey,
        'X-Goog-FieldMask: id,displayName,formattedAddress,location,rating,googleMapsUri,photos,nationalPhoneNumber,reviews,primaryType'
    ]);
    $response_body = curl_exec($ch);
    curl_close($ch);

    // Ambil wisata terdekat jika lokasi tersedia
    $response_body = json_decode($response_body, true);
    $lat = $response_body['location']['latitude'] ?? null;
    $lng = $response_body['location']['longitude'] ?? null;

    if ($lat && $lng) {
        $nearby_url = 'https://places.googleapis.com/v1/places:searchNearby';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $nearby_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: places.displayName,places.formattedAddress,places.id,places.location,places.rating,places.googleMapsUri,places.photos'
        ]);
        $request_data = [
            "includedPrimaryTypes" => [
                $response_body['primaryType'] ?? ''
            ],
            "includedTypes" => [
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
            "maxResultCount" => 4,
            "locationRestriction" => [
                "circle" => [
                    "center" => [
                        "latitude" => $lat,
                        "longitude" => $lng
                    ],
                    "radius" => 5000.0
                ]
            ]
        ];
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
        $response_body_terdekat = curl_exec($ch);
        curl_close($ch);

        // Anda bisa memproses $response_body_terdekat sesuai kebutuhan
        $nearby_places = json_decode($response_body_terdekat, true);
    }
}

$photos = [];
if (isset($response_body['photos']) && count($response_body['photos']) > 0) {
    foreach ($response_body['photos'] as $photo) {
        $photos[] = "ambil-foto.php?name=" . $photo["name"] . "&maxWidthPx=500";
    }
    $response_body['photos'] = $photos;
} else {
    $response_body['photos'] = ['images/dashboard/people.svg'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $response_body["displayName"]["text"] ?? "Tempat Wisata Tidak Ditemukan" ?></title>
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
            <!-- partial:partials/_settings-panel.html -->
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-6 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold"><?php echo $response_body["displayName"]["text"] ?? "Tempat Wisata Tidak Ditemukan" ?></h3>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6 col grid-margin stretch-card">
                            <div class="card bg-transparent text-center">
                                <div class="card-people p-0 d-flex justify-content-center align-items-center" style="height:100%;">
                                    <img
                                        src="<?php echo $response_body['photos']['0'] ?? 'images/dashboard/people.svg' ?>"
                                        alt="<?php echo $response_body["displayName"]["text"] ?? "Tempat Wisata" ?>"
                                        class="img-fluid w-100"
                                        style="max-height:50vh; object-fit:cover;" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-title">Rincian Tempat</p>
                                            <p class="font-weight-500">
                                                <?php echo $response_body["formattedAddress"] ?? "Alamat tidak ditemukan" ?>
                                            </p>
                                            <p> <a href="<?php echo $response_body["googleMapsUri"] ?? "" ?>"><i class="mdi mdi-google-maps"></i>Link Google Maps</a></p>
                                            <p>Telp: <?php echo $response_body["nationalPhoneNumber"] ?? "-" ?></p>
                                            <div class="d-flex flex-wrap mb-5">
                                                <div class="mr-5 mt-3">
                                                    <p class="text-muted">Rating</p>
                                                    <h3 class="text-primary fs-30 font-weight-medium">
                                                        <?php echo $response_body["rating"] ?? "0" ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="map" style="height: 400px;"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="directions-panel"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card position-relative">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col">
                                            <div
                                                id="detailedReports"
                                                class="carousel slide detailed-report-carousel position-static pt-2"
                                                data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php
                                                    foreach ($response_body['photos'] as $index => $photo) {
                                                        if ($index == 1) {
                                                            echo '<div class="carousel-item active">
                                                                <img src="' . $photo . '" alt="people" class="img-fluid rounded mx-auto d-block h-100" style="max-height:50vh; object-fit:cover;"  />
                                                            </div>';
                                                        } else {
                                                            echo '<div class="carousel-item">
                                                                <img src="' . $photo . '" alt="people" class="img-fluid rounded mx-auto d-block h-100" style="max-height:50vh; object-fit:cover;"  />
                                                            </div>';
                                                        }
                                                    } ?>
                                                </div>
                                                <a
                                                    class="carousel-control-prev"
                                                    href="#detailedReports"
                                                    role="button"
                                                    data-slide="prev">
                                                    <span
                                                        class="carousel-control-prev-icon"
                                                        aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a
                                                    class="carousel-control-next"
                                                    href="#detailedReports"
                                                    role="button"
                                                    data-slide="next">
                                                    <span
                                                        class="carousel-control-next-icon"
                                                        aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Review</h4>
                                    <div class="list-wrapper pt-2">
                                        <ul class="icon-data-list">
                                            <?php
                                            if (!isset($response_body['reviews']) || count($response_body['reviews']) == 0) {
                                                echo '<li class="text-center">Tidak ada review yang tersedia.</li>';
                                            } else {
                                                foreach ($response_body['reviews'] as $review) {
                                                    echo '<li>
                                                            <div class="d-flex">
                                                                <img src="ambil-foto-profil.php?url=' . $review["authorAttribution"]["photoUri"] . '" alt="user" />
                                                                <div>
                                                                    <p class="text-info mb-1">' . $review["authorAttribution"]["displayName"] . '</p>
                                                                    <p class="mb-0">' . (isset($review["originalText"]) ? $review["originalText"]["text"] : "") . '</p>
                                                                    <small class="text-info"><i class="mdi mdi-star"></i>   <strong>' . $review["rating"] . '</strong></small>
                                                                </div>
                                                            </div>
                                                        </li>';
                                                }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mt-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Wisata Yang Sejenis Dengan <?php echo $response_body["displayName"]["text"] ?? "" ?></h3>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row" id="nearby-places">
                        <?php
                        if (isset($nearby_places['places']) && count($nearby_places['places']) > 0) {
                            foreach ($nearby_places['places'] as $place) {
                                if ($place["id"] == $response_body["id"]) {
                                    continue; // Skip the current place if it matches the detail place
                                }
                                $photoUrl = isset($place["photos"]) && count($place["photos"]) > 0
                                    ? 'ambil-foto.php?name=' . $place["photos"][0]["name"] . '&maxWidthPx=470'
                                    : 'images/dashboard/people.svg';

                                echo '<div class="col-md-4 stretch-card grid-margin">' .
                                    '<a href="detail.php?place_id=' . $place["id"] . '" class="text-decoration-none">' .
                                    '<div class="card">' .
                                    '<div class="card-body">' .
                                    '<p class="card-title">' . ($place["displayName"]["text"] ?? 'Tempat Wisata') . '</p>' .
                                    '<img src="' . $photoUrl . '" alt="' . ($place["displayName"]["text"] ?? 'Tempat Wisata') . '" class="img-fluid rounded mb-3 d-block mx-auto" style="max-height:50vh; object-fit:cover;" />' .
                                    '<p class="text-black">' . ($place["formattedAddress"] ?? 'Alamat tidak tersedia') . '</p>' .
                                    '<p class="text-info"> <i class="mdi mdi-star"></i>   ' . ($place["rating"] ?? '0') . '</p>' .
                                    '<small class="text-black"><i class="mdi mdi-google-maps"></i>Google Maps: <a href="' . ($place["googleMapsUri"] ?? '#') . '" target="_blank">Link</a></small>' .
                                    '</div>' .
                                    '</div>' .
                                    '</a>' .
                                    '</div>';
                            }
                        } else {
                            echo '<div class="col-md-12"><p>Tidak ada wisata yang ditemukan.</p></div>';
                        }
                        ?>
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

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_ENV["GOOGLE_MAPS_API_KEY"] ?>&libraries=routes&callback=initMap" async defer></script>
    <script>
        // 1. Deklarasikan variabel penting di scope global agar bisa diakses antar fungsi
        let map;
        let directionsService;
        let directionsRenderer;
        let userLatitude = null;
        let userLongitude = null;
        const destinationLat = <?php echo $response_body['location']['latitude'] ?? 'null'; ?>;
        const destinationLng = <?php echo $response_body['location']['longitude'] ?? 'null'; ?>;

        // 2. Google akan memanggil fungsi ini HANYA JIKA API sudah siap
        function initMap() {
            // Inisialisasi layanan-layanan Google
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();

            // Inisialisasi peta dengan lokasi default (misal: lokasi tujuan)
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: {
                    lat: destinationLat || -6.807741995720486,
                    lng: destinationLng || 110.8417126190582
                },
            });

            // Hubungkan renderer ke peta dan panel
            directionsRenderer.setMap(map);
            directionsRenderer.setPanel(document.getElementById("directions-panel"));

            // Setelah peta siap, coba gambar rute.
            // Mungkin lokasi pengguna sudah ditemukan lebih dulu.
            calculateAndDisplayRoute();
        }

        // 3. Geolocation API akan memanggil fungsi ini jika berhasil mendapatkan lokasi
        function successCallback(position) {
            // Simpan koordinat pengguna ke variabel global
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;

            // Setelah lokasi ditemukan, coba gambar rute.
            // Mungkin peta sudah siap lebih dulu.
            calculateAndDisplayRoute();
        }

        function errorCallback(error) {
            console.warn(`ERROR(${error.code}): ${error.message}`);
            // Jika lokasi gagal didapat, tetap coba gambar rute dari Jakarta (atau lokasi default lain)
            // Anda bisa juga menampilkan pesan error kepada pengguna
            alert("Tidak bisa mendapatkan lokasi Anda. Rute akan dihitung dari lokasi default.");
            calculateAndDisplayRoute(); // Tetap panggil untuk menggambar rute dari default origin
        }

        // 4. Ini adalah fungsi utama untuk menggambar rute
        function calculateAndDisplayRoute() {
            // Fungsi ini hanya akan berjalan jika peta sudah siap (dipanggil dari initMap)
            // dan akan menggunakan lokasi pengguna jika sudah tersedia.
            if (!directionsService || !directionsRenderer) {
                // Jika peta/layanan belum siap, jangan lakukan apa-apa.
                return;
            }

            // Tentukan titik awal: gunakan lokasi pengguna jika ada, jika tidak gunakan lokasi default
            const originCoords = {
                lat: userLatitude || -6.807741995720486, // Default: Jakarta
                lng: userLongitude || 110.8417126190582
            };

            // Pastikan lokasi tujuan valid
            if (destinationLat === null || destinationLng === null) {
                alert("Lokasi tujuan tidak valid.");
                return;
            }

            const destinationCoords = {
                lat: destinationLat,
                lng: destinationLng
            };

            // Buat request
            const request = {
                origin: originCoords,
                destination: destinationCoords,
                travelMode: google.maps.TravelMode.DRIVING
            };

            // Kirim request
            directionsService.route(request, (response, status) => {
                if (status == 'OK') {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert('Permintaan rute gagal karena: ' + status);
                }
            });
        }

        // 5. Minta lokasi pengguna saat halaman dimuat
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            alert('Geolocation tidak didukung oleh browser ini. Rute akan dihitung dari lokasi default.');
            // Panggil calculateAndDisplayRoute jika geolocation tidak didukung sama sekali
            calculateAndDisplayRoute();
        }

        $(document).ready(function() {

            // Use CSS transitions for smoother animation
            $('#nearby-places').on('mouseenter', '.card', function() {
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
        });
    </script>
</body>

</html>