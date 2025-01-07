<?php
include '../../config.php';

// Validasi POST data
if (!isset($_POST['v_id_chat']) || !isset($_POST['input_chat'])) {
    echo "Error: Data POST tidak lengkap.";
    exit;
}

$v_id_chat = $_POST['v_id_chat'];
$input_chat = mysqli_real_escape_string($server, $_POST['input_chat']);

// Validasi $iduser
if (!isset($iduser)) {
    echo "Error: ID pengguna tidak ditemukan.";
    exit;
}

$time = time();

$kirim_chat = $server->query("INSERT INTO `chat`(`pengirim_user_id`, `penerima_user_id`, `text_chat`, `waktu`) VALUES ('$iduser', '$v_id_chat', '$input_chat', '$time')");

if ($kirim_chat) {
    echo "Pesan berhasil dikirim.";
} else {
    echo "Error: " . $server->error;
}
?>
