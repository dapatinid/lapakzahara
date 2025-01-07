<?php
include '../../config.php';

$nama_toko = htmlspecialchars(mysqli_real_escape_string($server, $_POST['nama_toko']));
$nama_pengguna = htmlspecialchars(mysqli_real_escape_string($server, $_POST['nama_pengguna'])); 
$no_wa = htmlspecialchars(mysqli_real_escape_string($server, $_POST['no_wa']));

// Ambil data username, nomor WhatsApp, dan logo toko yang sudah ada
$existing_data = $server->query("SELECT `nama_pengguna`, `no_whatsapp`, `logo_toko` FROM `akun` WHERE `id`='$iduser'")->fetch_assoc();

$existing_username = $existing_data['nama_pengguna'];
$existing_wa_number = $existing_data['no_whatsapp'];
$existing_logo_toko = $existing_data['logo_toko'];

// Check apakah ada perubahan pada username dan nomor WhatsApp
$change_username = ($nama_pengguna !== $existing_username);
$change_wa_number = ($no_wa !== $existing_wa_number);

// Check apakah username baru sudah ada di database (jika ada perubahan)
if ($change_username) {
    $check_username_query = $server->query("SELECT * FROM `akun` WHERE `nama_pengguna`='$nama_pengguna'");
    if ($check_username_query->num_rows > 0) {
        echo "Username Toko sudah ada"; // Username already exists, return an error message
        exit(); // Stop execution if there's an error
    }
}

// Check apakah nomor WhatsApp baru sudah terdaftar di database (jika ada perubahan)
if ($change_wa_number) {
    $check_wa_query = $server->query("SELECT * FROM `akun` WHERE `no_whatsapp`='$no_wa'");
    if ($check_wa_query->num_rows > 0) {
        echo "Nomor WhatsApp sudah terdaftar"; // WhatsApp number already exists, return an error message
        exit(); // Stop execution if there's an error
    }
}

// Jika tidak ada perubahan pada username dan nomor WhatsApp, atau setelah memastikan tidak ada duplikasi, maka lanjutkan dengan pembaruan profil
$img_name_random = round(microtime(true));

function compress($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }
    imagejpeg($image, $destination, $quality);
    return $destination;
}

if (!empty($_FILES["cfile_img_pro"]["name"])) {
    $expname1 = explode('.', $_FILES["cfile_img_pro"]["name"]);
    $ext1 = end($expname1);
    $name1 = $img_name_random . '.' . $ext1;
    $path1 = "../../assets/image/profil-toko/" . $name1;

    $source_img = $_FILES["cfile_img_pro"]["tmp_name"];
    $destination_img = $path1;

    $d = compress($source_img, $destination_img, 50);

    // Hapus gambar lama jika bukan gambar default dan gambar baru tidak sama dengan yang lama
    if ($existing_logo_toko != 'user.png' && $name1 != $existing_logo_toko) {
        unlink('../../assets/image/profil-toko/' . $existing_logo_toko);
    }
} else {
    $name1 = $existing_logo_toko;
}

$update_ep = $server->query("UPDATE `akun` SET `logo_toko`='$name1', `nama_toko`='$nama_toko', `nama_pengguna`='$nama_pengguna', `no_whatsapp`='$no_wa', `verifikasi_toko`='Tidak', `status_toko`='Aktif', `level_toko`='Bronze' WHERE `id`='$iduser'");

if ($update_ep) {
    echo "Profil berhasil diperbarui"; // Return success message
}
?>
