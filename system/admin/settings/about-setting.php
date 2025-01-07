<?php
include '../../../config.php';

$kp_about = $_POST['kp_about'];
$tk_about = $_POST['tk_about'];
$sk_about = $_POST['sk_about'];


$up_kp_ab = $server->query("UPDATE `setting_about` SET `isi`='$kp_about' WHERE `id`='1' ");
$up_tk_ab = $server->query("UPDATE `setting_about` SET `isi`='$tk_about' WHERE `id`='2' ");
$up_sk_ab = $server->query("UPDATE `setting_about` SET `isi`='$sk_about' WHERE `id`='3' ");


?>
<script>
    text_s_abt.innerHTML = 'Berhasil Disimpan';
    setTimeout(() => {
        text_s_abt.innerHTML = 'Simpan';
    }, 2000);
</script>