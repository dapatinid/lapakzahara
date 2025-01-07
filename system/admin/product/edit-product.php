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

$id_produk_ep = $_POST['id_produk_ep'];
$judul_ep = mysqli_real_escape_string($server, $_POST['judul_ep']);
$slug_ep = mysqli_real_escape_string($server, $_POST['slug_ep']);
$harga_ep = str_replace('.', '', $_POST['harga_ep']);
$kategori_ep = mysqli_real_escape_string($server, $_POST['kategori_ep']);
$brand_ep = mysqli_real_escape_string($server, $_POST['brand_ep']);
$berat_ep = str_replace('.', '', $_POST['berat_ep']);
$stok_ep = str_replace('.', '', $_POST['stok_ep']);
$deskripsi_ep = mysqli_real_escape_string($server, $_POST['deskripsi_ep']);
$diskon_ep = str_replace('.', '', $_POST['diskon_ep']);
$kondisi_ep = mysqli_real_escape_string($server, $_POST['kondisi_ep']);
$gratis_ongkir_ep = mysqli_real_escape_string($server, $_POST['gratis_ongkir_ep']);
$id_lokasi_ep = mysqli_real_escape_string($server, $_POST['id_lokasi_ep']);
$lokasi_ep = mysqli_real_escape_string($server, $_POST['lokasi_ep']);
$varian_warna = substr($_POST['varian_warna_ep'], 1);
$varian_ukuran = substr($_POST['varian_ukuran_ep'], 1);

$val_img_ed_ep1 = $_POST['val_img_ed_ep1'];
$val_img_ed_ep2 = $_POST['val_img_ed_ep2'];
$val_img_ed_ep3 = $_POST['val_img_ed_ep3'];
$val_img_ed_ep4 = $_POST['val_img_ed_ep4'];
$val_img_ed_ep5 = $_POST['val_img_ed_ep5'];
$val_img_ed_ep6 = $_POST['val_img_ed_ep6'];
$val_img_ed_ep7 = $_POST['val_img_ed_ep7'];
$val_img_ed_ep8 = $_POST['val_img_ed_ep8'];
$val_img_ed_ep9 = $_POST['val_img_ed_ep9'];
$val_img_ed_ep10 = $_POST['val_img_ed_ep10'];
$val_img_ed_ep11 = $_POST['val_img_ed_ep11'];
$val_img_ed_ep12 = $_POST['val_img_ed_ep12'];
$val_img_ed_ep13 = $_POST['val_img_ed_ep13'];
$val_img_ed_ep14 = $_POST['val_img_ed_ep14'];
$val_img_ed_ep15 = $_POST['val_img_ed_ep15'];
$val_img_ed_ep16 = $_POST['val_img_ed_ep16'];
$val_img_ed_ep17 = $_POST['val_img_ed_ep17'];
$val_img_ed_ep18 = $_POST['val_img_ed_ep18'];
$val_img_ed_ep19 = $_POST['val_img_ed_ep19'];
$val_img_ed_ep20 = $_POST['val_img_ed_ep20'];

$img_name_random = round(microtime(true));

function compressAndUploadImage($fileInputName, $img_name_random) {
    global $img_name_random;

    $name = $img_name_random . '-' . $fileInputName . '.' . pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
    $path = "../../../assets/image/product/" . $name;

    if (!empty($_FILES[$fileInputName]["name"])) {
        $source = $_FILES[$fileInputName]["tmp_name"];
        $compressedPath = "../../../assets/image/product/compressed/" . $name;
        $apiKey = $tinypng_key; // Ganti dengan kunci API TinyPNG Anda

        // Kompress gambar menggunakan fungsi TinyPNG
        compressImageWithTinyPNG($source, $compressedPath, $apiKey);

        // Hapus file asli setelah berhasil mengompresi
        unlink($path);

        return $name . ',';
    } else {
        return '';
    }
}

if ($val_img_ed_ep1 == '1') {
    $nametodb1 = compressAndUploadImage("c_img_ep_1", $img_name_random);
} else if ($val_img_ed_ep1 == '') {
    $nametodb1 = '';
} else {
    $nametodb1 = $val_img_ed_ep1 . ',';
}

if ($val_img_ed_ep2 == '1') {
    $nametodb2 = compressAndUploadImage("c_img_ep_2", $img_name_random);
} else if ($val_img_ed_ep2 == '') {
    $nametodb2 = '';
} else {
    $nametodb2 = $val_img_ed_ep2 . ',';
}

if ($val_img_ed_ep3 == '1') {
    $nametodb3 = compressAndUploadImage("c_img_ep_3", $img_name_random);
} else if ($val_img_ed_ep3 == '') {
    $nametodb3 = '';
} else {
    $nametodb3 = $val_img_ed_ep3 . ',';
}

if ($val_img_ed_ep4 == '1') {
    $nametodb4 = compressAndUploadImage("c_img_ep_4", $img_name_random);
} else if ($val_img_ed_ep4 == '') {
    $nametodb4 = '';
} else {
    $nametodb4 = $val_img_ed_ep4 . ',';
}

if ($val_img_ed_ep5 == '1') {
    $nametodb5 = compressAndUploadImage("c_img_ep_5", $img_name_random);
} else if ($val_img_ed_ep5 == '') {
    $nametodb5 = '';
} else {
    $nametodb5 = $val_img_ed_ep5 . ',';
}

if ($val_img_ed_ep6 == '1') {
    $nametodb6 = compressAndUploadImage("c_img_ep_6", $img_name_random);
} else if ($val_img_ed_ep6 == '') {
    $nametodb6 = '';
} else {
    $nametodb6 = $val_img_ed_ep6 . ',';
}

if ($val_img_ed_ep7 == '1') {
    $nametodb7 = compressAndUploadImage("c_img_ep_7", $img_name_random);
} else if ($val_img_ed_ep7 == '') {
    $nametodb7 = '';
} else {
    $nametodb7 = $val_img_ed_ep7 . ',';
}

if ($val_img_ed_ep8 == '1') {
    $nametodb8 = compressAndUploadImage("c_img_ep_8", $img_name_random);
} else if ($val_img_ed_ep8 == '') {
    $nametodb8 = '';
} else {
    $nametodb8 = $val_img_ed_ep8 . ',';
}

if ($val_img_ed_ep9 == '1') {
    $nametodb9 = compressAndUploadImage("c_img_ep_9", $img_name_random);
} else if ($val_img_ed_ep9 == '') {
    $nametodb9 = '';
} else {
    $nametodb9 = $val_img_ed_ep9 . ',';
}

if ($val_img_ed_ep10 == '1') {
    $nametodb10 = compressAndUploadImage("c_img_ep_10", $img_name_random);
} else if ($val_img_ed_ep10 == '') {
    $nametodb10 = '';
} else {
    $nametodb10 = $val_img_ed_ep10 . ',';
}

if ($val_img_ed_ep11 == '1') {
    $nametodb11 = compressAndUploadImage("c_img_ep_11", $img_name_random);
} else if ($val_img_ed_ep11 == '') {
    $nametodb11 = '';
} else {
    $nametodb11 = $val_img_ed_ep11 . ',';
}

if ($val_img_ed_ep12 == '1') {
    $nametodb12 = compressAndUploadImage("c_img_ep_12", $img_name_random);
} else if ($val_img_ed_ep12 == '') {
    $nametodb12 = '';
} else {
    $nametodb12 = $val_img_ed_ep12 . ',';
}

if ($val_img_ed_ep13 == '1') {
    $nametodb13 = compressAndUploadImage("c_img_ep_13", $img_name_random);
} else if ($val_img_ed_ep13 == '') {
    $nametodb13 = '';
} else {
    $nametodb13 = $val_img_ed_ep13 . ',';
}

if ($val_img_ed_ep14 == '1') {
    $nametodb14 = compressAndUploadImage("c_img_ep_14", $img_name_random);
} else if ($val_img_ed_ep14 == '') {
    $nametodb14 = '';
} else {
    $nametodb14 = $val_img_ed_ep14 . ',';
}

if ($val_img_ed_ep15 == '1') {
    $nametodb15 = compressAndUploadImage("c_img_ep_15", $img_name_random);
} else if ($val_img_ed_ep15 == '') {
    $nametodb15 = '';
} else {
    $nametodb15 = $val_img_ed_ep15 . ',';
}

if ($val_img_ed_ep16 == '1') {
    $nametodb16 = compressAndUploadImage("c_img_ep_16", $img_name_random);
} else if ($val_img_ed_ep16 == '') {
    $nametodb16 = '';
} else {
    $nametodb16 = $val_img_ed_ep16 . ',';
}

if ($val_img_ed_ep17 == '1') {
    $nametodb17 = compressAndUploadImage("c_img_ep_17", $img_name_random);
} else if ($val_img_ed_ep17 == '') {
    $nametodb17 = '';
} else {
    $nametodb17 = $val_img_ed_ep17 . ',';
}

if ($val_img_ed_ep18 == '1') {
    $nametodb18 = compressAndUploadImage("c_img_ep_18", $img_name_random);
} else if ($val_img_ed_ep18 == '') {
    $nametodb18 = '';
} else {
    $nametodb18 = $val_img_ed_ep18 . ',';
}

if ($val_img_ed_ep19 == '1') {
    $nametodb19 = compressAndUploadImage("c_img_ep_19", $img_name_random);
} else if ($val_img_ed_ep19 == '') {
    $nametodb19 = '';
} else {
    $nametodb19 = $val_img_ed_ep19 . ',';
}

if ($val_img_ed_ep20 == '1') {
    $nametodb20 = compressAndUploadImage("c_img_ep_20", $img_name_random);
} else if ($val_img_ed_ep20 == '') {
    $nametodb20 = '';
} else {
    $nametodb20 = $val_img_ed_ep20 . ',';
}

 
// Lakukan hal serupa untuk $val_img_ed_ep2 sampai $val_img_ed_ep5

$name_gambar_add = $nametodb1 . $nametodb2 . $nametodb3 . $nametodb4 . $nametodb5 . $nametodb6 . $nametodb7 . $nametodb8 . $nametodb9 . $nametodb10 . $nametodb11 . $nametodb12 . $nametodb13 . $nametodb14 . $nametodb15 . $nametodb16 . $nametodb17 . $nametodb18 . $nametodb19 . $nametodb20;
$name_gambar = substr($name_gambar_add, 0, -1);
$waktu_diperbarui = date('Y-m-d H:i:s'); // Ambil waktu saat ini
$edit_produk_adm = $server->query("UPDATE `iklan` SET `id_kategori`='$kategori_ep', `id_brand`='$brand_ep', `gambar`='$name_gambar',`judul`='$judul_ep',`slug`='$slug_ep',`harga`='$harga_ep',`deskripsi`='$deskripsi_ep',`diskon`='$diskon_ep',`kondisi`='$kondisi_ep',`gratis_ongkir`='$gratis_ongkir_ep', `id_lokasi`='$id_lokasi_ep', `lokasi`='$lokasi_ep', `berat`='$berat_ep',`warna`='$varian_warna',`ukuran`='$varian_ukuran',`stok`='$stok_ep', `waktu_diperbarui`='$waktu_diperbarui', `status_moderasi`='Menunggu Moderasi' WHERE `id`='$id_produk_ep' ");

if ($edit_produk_adm) {
?>
    <script>
        location.reload(); // Reload halaman saat proses selesai
    </script>
<?php
}
?>
