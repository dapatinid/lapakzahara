<?php
include '../../../config.php';

$nama_lengkap_edt = mysqli_real_escape_string($server, $_POST['nama_lengkap_edt']);
$verifikasi_user_edt = mysqli_real_escape_string($server, $_POST['verifikasi_user_edt']);
$email_edt = mysqli_real_escape_string($server, $_POST['email_edt']);
$no_wa_edt = mysqli_real_escape_string($server, $_POST['no_wa_edt']);
$tipe_akun_edt = mysqli_real_escape_string($server, $_POST['tipe_akun_edt']);
$id_user_edit_akun = mysqli_real_escape_string($server, $_POST['id_user_edit_akun']);
$saldo_edt = str_replace('.', '', $_POST['saldo_edt']);

// Update kolom 'nama_lengkap', 'email', 'no_whatsapp', 'tipe_akun' di tabel 'akun'
$edit_akun = $server->query("UPDATE `akun` SET `nama_lengkap`='$nama_lengkap_edt', `verifikasi_user`='$verifikasi_user_edt', `email`='$email_edt', `no_whatsapp`='$no_wa_edt', `tipe_akun`='$tipe_akun_edt' WHERE `id`='$id_user_edit_akun'");

// Cek apakah user_id sudah ada di tabel 'saldo'
$result = $server->query("SELECT COUNT(*) as count FROM `saldo` WHERE `user_id`='$id_user_edit_akun'");
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // Jika user_id sudah ada, lakukan update
    $update_saldo = $server->query("UPDATE `saldo` SET `jumlah_saldo`='$saldo_edt' WHERE `user_id`='$id_user_edit_akun'");
} else {
    // Jika user_id belum ada, lakukan insert
    $update_saldo = $server->query("INSERT INTO `saldo` (`user_id`, `jumlah_saldo`) VALUES ('$id_user_edit_akun', '$saldo_edt')");
}

if ($edit_akun && $update_saldo) {
    echo "Akun berhasil diperbarui.";
} else {
    echo "Terjadi kesalahan. Gagal memperbarui akun.";
}
?>
<script>
    var boxEditAkun = document.getElementById('box_edit_akun');
    if (boxEditAkun) {
        boxEditAkun.style.display = 'none';
        window.location.href = 'index.php';
    } else {
        console.error("Element dengan ID box_edit_akun tidak ditemukan.");
    }
</script>
