<?php
include '../../config.php';

// Fungsi untuk membersihkan dan memvalidasi input
function clean_and_validate_input($input) {
    global $server;
    $cleaned_input = mysqli_real_escape_string($server, $input);
    $cleaned_input = htmlspecialchars($cleaned_input);
    return $cleaned_input;
}

$nama_lengkap = clean_and_validate_input($_POST['nama_lengkap']);
$email = clean_and_validate_input($_POST['email']); 
$no_wa = clean_and_validate_input($_POST['no_wa']);

// Ambil data username dan nomor WhatsApp yang sudah ada
$existing_data = $server->query("SELECT `email`, `no_whatsapp`, `foto` FROM `akun` WHERE `id`='$iduser'")->fetch_assoc();

$existing_email = $existing_data['email'];
$existing_wa_number = $existing_data['no_whatsapp'];
$existing_foto = $existing_data['foto'];

// Check apakah ada perubahan pada email dan nomor WhatsApp
$change_email = ($email !== $existing_email);
$change_wa_number = ($no_wa !== $existing_wa_number);

// Check apakah email baru sudah ada di database (jika ada perubahan)
if ($change_email) {
    $check_email_query = $server->query("SELECT * FROM `akun` WHERE `email`='$email'");
    if ($check_email_query->num_rows > 0) {
        echo "Email sudah terdaftar"; // Email already exists, return an error message
        exit(); // Stop execution if there's an error
    }
}

// Check apakah nomor WhatsApp baru sudah terdaftar di database (jika ada perubahan)
if ($change_wa_number) {
    $check_wa_query = $server->query("SELECT * FROM `akun` WHERE `no_whatsapp`='$no_wa'");
    if ($check_wa_query->num_rows > 0) {
        echo "Nomor sudah terdaftar"; // WhatsApp number already exists, return an error message
        exit(); // Stop execution if there's an error
    }
}

// Jika tidak ada perubahan pada email dan nomor WhatsApp, atau setelah memastikan tidak ada duplikasi, maka lanjutkan dengan pembaruan profil
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
    $path1 = "../../assets/image/profile/" . $name1;

    $source_img = $_FILES["cfile_img_pro"]["tmp_name"];
    $destination_img = $path1;

    $d = compress($source_img, $destination_img, 50);

    // Hapus gambar lama jika bukan gambar default dan gambar baru tidak sama dengan yang lama
    if ($existing_foto != 'user.png' && $name1 != $existing_foto) {
        unlink('../../assets/image/profile/' . $existing_foto);
    }
} else {
    $name1 = $existing_foto;
}

$update_ep = $server->query("UPDATE `akun` SET `foto`='$name1',`nama_lengkap`='$nama_lengkap',`email`='$email',`no_whatsapp`='$no_wa' WHERE `id`='$iduser'");

if ($update_ep) {
    echo "Profil berhasil diperbarui"; // Return success message
}
?>
