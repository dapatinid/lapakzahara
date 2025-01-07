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
        $compressedPath = "../../../assets/icons/category/compressed/" . basename($imagePath); // Simpan ke direktori compressed
        file_put_contents($compressedPath, $compressedImageData);

        return $compressedPath;
    } else {
        return false;
    }
}

$nama_kategori_edit = $_POST['nama_kategori_edit'];
$slug_kategori_edit = $_POST['slug_kategori_edit']; // Menambahkan variabel slug
$val_id_kat_hapus = $_POST['val_id_kat_hapus'];

if (empty($_FILES["icon_file_edit"]["name"])) {
    $update_kategori = $server->query("UPDATE `kategori` SET `nama`='$nama_kategori_edit', `slug`='$slug_kategori_edit' WHERE `id`='$val_id_kat_hapus'"); // Menambahkan slug dalam query
    if ($update_kategori) {
?>
        <script>
            window.location.href = 'index.php';
        </script>
<?php
    }
} else {
    $ext = end(explode('.', $_FILES["icon_file_edit"]["name"]));
    $name = md5(rand()) . '.' . $ext;
    $path = "../../../assets/icons/category/" . $name;

    if (move_uploaded_file($_FILES["icon_file_edit"]["tmp_name"], $path)) {
        // Menggunakan API TinyPNG untuk kompresi gambar
        $apiKey = $tinypng_key; // Ganti dengan kunci API TinyPNG Anda

        $compressedPath = compressImageWithTinyPNG($path, $apiKey);

        if ($compressedPath !== false) {
            // Hapus file asli setelah berhasil mengompresinya
            unlink($path);

            $update_kategori = $server->query("UPDATE `kategori` SET `nama`='$nama_kategori_edit', `slug`='$slug_kategori_edit', `icon`='$name' WHERE `id`='$val_id_kat_hapus'"); // Menambahkan slug dalam query
            if ($update_kategori) {
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
