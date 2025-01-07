<?php
include '../../../config.php';

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
        $compressedPath = "../../../assets/icons/brand/compressed/" . basename($imagePath); // Simpan ke direktori compressed
        file_put_contents($compressedPath, $compressedImageData);

        return $compressedPath;
    } else {
        return false;
    }
}

$namab_brand_edit = $_POST['namab_brand_edit'];
$slug_brand_edit = $_POST['slug_brand_edit']; // Menambahkan variabel slug
$val_id_kat_hapus = $_POST['val_id_kat_hapus'];

if (empty($_FILES["icon_file_edit"]["name"])) {
    $update_brand = $server->query("UPDATE `brand` SET `namab`='$namab_brand_edit', `slug`='$slug_brand_edit' WHERE `id`='$val_id_kat_hapus'"); // Menambahkan slug dalam query
    if ($update_brand) {
?>
        <script>
            window.location.href = 'index.php';
        </script>
<?php
    }
} else {
    $ext = end(explode('.', $_FILES["icon_file_edit"]["name"]));
    $name = md5(rand()) . '.' . $ext;
    $path = "../../../assets/icons/brand/" . $name;

    if (move_uploaded_file($_FILES["icon_file_edit"]["tmp_name"], $path)) {
        // Menggunakan API TinyPNG untuk kompresi gambar
        $apiKey = $tinypng_key; // Ganti dengan kunci API TinyPNG Anda

        $compressedPath = compressImageWithTinyPNG($path, $apiKey);

        if ($compressedPath !== false) {
            // Hapus file asli setelah berhasil mengompresinya
            unlink($path);

            $update_brand = $server->query("UPDATE `brand` SET `namab`='$namab_brand_edit', `slug`='$slug_brand_edit', `icon`='$name' WHERE `id`='$val_id_kat_hapus'"); // Menambahkan slug dalam query
            if ($update_brand) {
            ?>
                <script>
        location.reload(); // Reload halaman saat proses selesai
    </script>
<?php
            }
        } else {
            // Penanganan kesalahan jika kompresi gagal
            echo "Gagal mengompresi gambar dengan TinyPNG.";
        }
    }
}
?>
