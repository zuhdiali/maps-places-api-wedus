<?php

require __DIR__ . '/vendor/autoload.php'; // Jika pakai Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Sekarang Anda bisa mengakses API Key
$apiKey = $_ENV['GOOGLE_PLACES_API_KEY'];


// 1. Ambil parameter dari permintaan klien (dari URL ?name=...&maxWidthPx=...)
// Kita gunakan filter_input untuk keamanan dasar.
$photoName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_URL);
$maxWidthPx = filter_input(INPUT_GET, 'maxWidthPx', FILTER_VALIDATE_INT, [
    'options' => [
        'default' => 400, // Nilai default jika tidak disediakan
        'min_range' => 1,
        'max_range' => 4800
    ]
]);

// 2. Validasi input
if (empty($photoName)) {
    // Kirim header status error dan hentikan eksekusi
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parameter "name" (nama foto) dibutuhkan.']);
    exit;
}

// 3. Bangun URL lengkap ke Google Maps API
$googleApiUrl = "https://places.googleapis.com/v1/{$photoName}/media?key={$apiKey}&maxWidthPx={$maxWidthPx}";

// 4. Gunakan cURL untuk membuat permintaan dari server Anda ke server Google
// Pastikan ekstensi php-curl sudah aktif di server Anda (biasanya sudah standar)
$ch = curl_init();

// Set opsi untuk cURL
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Mengembalikan hasil sebagai string, bukan langsung output
curl_setopt($ch, CURLOPT_HEADER, false);        // Kita tidak butuh header dari Google di body respons
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Ikuti redirect jika ada (Google Photos sering melakukan ini)

// Eksekusi permintaan cURL
$image_data = curl_exec($ch);

// Periksa status HTTP dan tipe konten (content type)
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// Tutup koneksi cURL
curl_close($ch);

// 5. Teruskan respons dari Google ke browser pengguna
if ($http_code == 200 && $image_data) {
    // Jika berhasil (kode 200), kirim header Content-Type yang benar
    header("Content-Type: {$content_type}");
    header('Cache-Control: public, max-age=86400'); // Opsional: cache gambar di browser selama 1 hari

    // Tampilkan data gambar
    echo $image_data;
} else {
    // Jika gagal, kirim status error
    http_response_code($http_code ?: 500); // Gunakan kode dari Google, atau 500 jika cURL gagal total
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Gagal mengambil gambar dari Google.', 'status' => $http_code]);
}

exit;
