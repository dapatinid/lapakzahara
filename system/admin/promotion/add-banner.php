<?php
include '../../../config.php';

$ext = end(explode('.', $_FILES["pilih_banner"]["name"]));
$name = md5(rand()) . '.' . $ext;
$path = "../../../assets/image/banner/" . $name;

if (move_uploaded_file($_FILES["pilih_banner"]["tmp_name"], $path)) {
    // Menggunakan API TinyPNG untuk kompresi gambar
    $apiKey = $tinypng_key; // Ganti dengan kunci API TinyPNG Anda
    $compressedPath = compressImageWithTinyPNG($path, $apiKey);

    if ($compressedPath !== false) {
        // Hapus file asli setelah berhasil mengompresinya
        unlink($path);

        // Proses penyimpanan gambar yang telah dikompresi ke database atau lokasi yang diinginkan
        $insert_add_banner = $server->query("INSERT INTO `banner_promo`(`image`, `status`) VALUES ('$name', '')");
        if ($insert_add_banner) {
            echo "<script>window.location.href = 'index.php';</script>";
            exit; // Pastikan untuk keluar setelah melakukan redirect
        } else {
            echo "Gagal menyimpan informasi gambar ke database.";
        }
    } else {
        // Penanganan kesalahan jika kompresi gagal
        echo "Gagal mengompresi gambar dengan TinyPNG.";
    }
} else {
    echo "Gagal mengunggah file gambar.";
}

// Fungsi untuk mengompresi gambar dengan TinyPNG menggunakan kunci API
function compressImageWithTinyPNG($imagePath, $apiKey) {
    $url = "https://api.tinify.com/shrink";
    $imageData = file_get_contents($imagePath);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\nAuthorization: Basic " . base64_encode("api:$apiKey"),
            'method'  => 'POST',
            'content' => $imageData
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result !== false) {
        $compressedData = json_decode($result, true);
        $compressedURL = $compressedData['output']['url'];

        // Download gambar yang telah dikompresi dan simpan di lokasi yang diinginkan
        $compressedImageData = file_get_contents($compressedURL);
        $compressedPath = "../../../assets/image/banner/compressed/" . basename($imagePath); // Lokasi baru
        file_put_contents($compressedPath, $compressedImageData);

        return $compressedPath;
    } else {
        return false;
    }
}
?>
