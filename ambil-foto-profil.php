<?php

// Ambil URL perantara dari parameter, misalnya: ambil-gambar-profil.php?url=http://...
$photo_uri = filter_input(INPUT_GET, 'url', FILTER_VALIDATE_URL);

if (!$photo_uri) {
    http_response_code(400);
    echo "URL tidak valid atau tidak disediakan.";
    exit;
}

// Inisialisasi cURL
$ch = curl_init();

// Set opsi untuk cURL
curl_setopt($ch, CURLOPT_URL, $photo_uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// PENTING: Perintahkan cURL untuk mengikuti redirect dari Google
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Eksekusi permintaan. cURL akan otomatis mengikuti redirect dan mengambil data gambar final.
$image_data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

curl_close($ch);

// Jika permintaan berhasil, kirim data gambar ke browser
if ($http_code == 200 && $image_data) {
    // Kirim header tipe konten yang benar (misal: image/jpeg)
    header("Content-Type: {$content_type}");
    header('Cache-Control: public, max-age=86400'); // Cache gambar selama 1 hari

    // Tampilkan data gambar
    echo $image_data;
} else {
    // Jika gagal, kirim status error atau tampilkan gambar default
    http_response_code($http_code ?: 500);
    // Anda bisa juga melakukan redirect ke gambar placeholder
    // header('Location: /path/to/default-avatar.png');
}

exit;
