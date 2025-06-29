<?php
require __DIR__ . '/vendor/autoload.php'; // Jika pakai Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Sekarang Anda bisa mengakses API Key
$apiKey = $_ENV['GOOGLE_PLACES_API_KEY'];

// Mengatur header respons menjadi JSON, karena kita akan meneruskan data JSON
header('Content-Type: application/json');

// Places API (New) menggunakan metode POST untuk permintaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Metode yang diizinkan hanya POST', 'method' => $_SERVER['REQUEST_METHOD']]);
    exit;
}

// 1. Baca body permintaan JSON yang dikirim dari JavaScript
$client_request_body = file_get_contents('php://input');
if (empty($client_request_body)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Request body tidak boleh kosong.']);
    exit;
}

// URL endpoint Places API (New). Contoh: searchNearby
// Anda bisa membuatnya lebih dinamis jika perlu menangani endpoint lain.
$nearby_url = 'https://places.googleapis.com/v1/places:searchNearby';

// 2. Siapkan cURL untuk meneruskan permintaan ke Google
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $nearby_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $client_request_body); // Teruskan body dari klien

// 3. Set header yang dibutuhkan oleh Places API (New)
// Ini adalah bagian terpenting
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Goog-Api-Key: ' . $apiKey, // API Key dikirim di header
    // FieldMask dikirim dari klien untuk efisiensi
    'X-Goog-FieldMask: places.displayName,places.formattedAddress,places.id,places.location,places.rating,places.googleMapsUri,places.photos,places.nationalPhoneNumber'
]);

// Eksekusi cURL
$response_body = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Tutup koneksi
curl_close($ch);

// 4. Periksa hasil dan teruskan ke klien
if ($http_code >= 200 && $http_code < 300) {
    // Jika sukses, langsung echo respons JSON dari Google
    echo $response_body;
} else {
    // Jika gagal, kirim status error yang sesuai
    http_response_code($http_code);
    echo $response_body; // Teruskan pesan error dari Google
}

exit;
