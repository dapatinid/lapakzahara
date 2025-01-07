<?php
include '../../../config.php';

// Fungsi untuk kompresi gambar dengan TinyPNG API
function compressImageWithTinyPNG($source, $destination) {
    global $tinypng_key; // Memanggil variabel global $tinypng_key

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.tinify.com/shrink");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($source));
    curl_setopt($ch, CURLOPT_USERPWD, "api:" . $tinypng_key); // Menggunakan variabel global $tinypng_key

    $result = curl_exec($ch);
    curl_close($ch);

    $output = json_decode($result);

    if ($output && isset($output->output->url)) {
        $compressedImageURL = $output->output->url;
        file_put_contents($destination, file_get_contents($compressedImageURL));
        return true;
    } else {
        return false;
    }
} 

$judul_tp = mysqli_real_escape_string($server, $_POST['judul_tp']);
$slug_tp = mysqli_real_escape_string($server, $_POST['slug_tp']);
$harga_tp = str_replace('.', '', $_POST['harga_tp']);
$kategori_tp = mysqli_real_escape_string($server, $_POST['kategori_tp']);
$brand_tp = mysqli_real_escape_string($server, $_POST['brand_tp']);
$berat_tp = str_replace('.', '', $_POST['berat_tp']);
$stok_tp = str_replace('.', '', $_POST['stok_tp']);
$deskripsi_tp = mysqli_real_escape_string($server, $_POST['deskripsi_tp']);
$diskon_tp = str_replace('.', '', $_POST['diskon_tp']);
$kondisi_tp = mysqli_real_escape_string($server, $_POST['kondisi_tp']);
$gratis_ongkir_tp = mysqli_real_escape_string($server, $_POST['gratis_ongkir_tp']);
$id_lokasi_tp = mysqli_real_escape_string($server, $_POST['id_lokasi_tp']);
$lokasi_tp = mysqli_real_escape_string($server, $_POST['lokasi_tp']);
$varian_warna = substr($_POST['varian_warna'], 1);
$varian_ukuran = substr($_POST['varian_ukuran'], 1);
$tipe_user_vt = $_POST['tipe_user_vt'];

$time = date("Y-m-d H:i:s");

$img_name_random = round(microtime(true));

// Fungsi untuk mengompresi gambar dengan TinyPNG API
function compressImage($fileInputName, $img_name_random) {
    $name = $img_name_random . '-' . $fileInputName . '.' . pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
    $path = "../../../assets/image/product/" . $name;

    if (!empty($_FILES[$fileInputName]["name"])) {
        move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $path);
        $compressedPath = "../../../assets/image/product/compressed/" . $name;
        $apiKey = $tinypng_key; // Ganti dengan kunci API TinyPNG Anda
        compressImageWithTinyPNG($path, $compressedPath, $apiKey);

        // Hapus file asli setelah berhasil mengompresi
        unlink($path);

        return $name . ',';
    } else {
        return '';
    }
}

// Mengompresi gambar 1 hingga 5 terpisah
$nametodb1 = compressImage("c_img_tp_1", $img_name_random);
$nametodb2 = compressImage("c_img_tp_2", $img_name_random);
$nametodb3 = compressImage("c_img_tp_3", $img_name_random);
$nametodb4 = compressImage("c_img_tp_4", $img_name_random);
$nametodb5 = compressImage("c_img_tp_5", $img_name_random);
$nametodb6 = compressImage("c_img_tp_6", $img_name_random);
$nametodb7 = compressImage("c_img_tp_7", $img_name_random);
$nametodb8 = compressImage("c_img_tp_8", $img_name_random);
$nametodb9 = compressImage("c_img_tp_9", $img_name_random);
$nametodb10 = compressImage("c_img_tp_10", $img_name_random);
$nametodb11 = compressImage("c_img_tp_11", $img_name_random);
$nametodb12 = compressImage("c_img_tp_12", $img_name_random);
$nametodb13 = compressImage("c_img_tp_13", $img_name_random);
$nametodb14 = compressImage("c_img_tp_14", $img_name_random);
$nametodb15 = compressImage("c_img_tp_15", $img_name_random);
$nametodb16 = compressImage("c_img_tp_16", $img_name_random);
$nametodb17 = compressImage("c_img_tp_17", $img_name_random);
$nametodb18 = compressImage("c_img_tp_18", $img_name_random);
$nametodb19 = compressImage("c_img_tp_19", $img_name_random);
$nametodb20 = compressImage("c_img_tp_20", $img_name_random);


$name_gambar_add = $nametodb1 . $nametodb2 . $nametodb3 . $nametodb4 . $nametodb5 . $nametodb6 . $nametodb7 . $nametodb8 . $nametodb9 . $nametodb10 . $nametodb11 . $nametodb12 . $nametodb13 . $nametodb14 . $nametodb15 . $nametodb16 . $nametodb17 . $nametodb18 . $nametodb19 . $nametodb20;
$name_gambar = substr($name_gambar_add, 0, -1);

if ($tipe_user_vt == 'store') {
    $iduser_tp = $iduser;
}else{
    $iduser_tp = 1;
}
 
$insert_product_adm = $server->query("INSERT INTO `iklan`(`user_id`, `id_kategori`, `id_brand`, `gambar`, `judul`, `slug`, `harga`, `deskripsi`, `diskon`, `kondisi`, `gratis_ongkir`, `id_lokasi`, `lokasi`, `berat`, `warna`, `ukuran`, `stok`, `terjual`, `tipe_iklan`, `waktu`, `waktu_diperbarui`, `status`) VALUES ('$iduser_tp', '$kategori_tp', '$brand_tp', '$name_gambar', '$judul_tp', '$slug_tp', '$harga_tp', '$deskripsi_tp', '$diskon_tp', '$kondisi_tp', '$gratis_ongkir_tp', '$id_lokasi_tp', '$lokasi_tp', '$berat_tp', '$varian_warna', '$varian_ukuran', '$stok_tp', '0', '', '$time', '$time', '')");

if ($insert_product_adm) {
?>
    <script>
        location.reload(); // Reload halaman saat proses selesai
    </script>
<?php
}
