<?php
include '../../../config.php';

$time_fs_ub = $_POST['time_fs_ub'];

// Periksa apakah ada data flashsale dengan id_fs=1
$check_flashsale = $server->query("SELECT * FROM `gratis_ongkir` WHERE `id_gratis_ongkir`='1'");
if ($check_flashsale && $check_flashsale->num_rows > 0) {
    // Jika data sudah ada, lakukan UPDATE
    $update_time_fs_adm = $server->query("UPDATE `gratis_ongkir` SET `waktu_berakhir`='$time_fs_ub' WHERE `id_gratis_ongkir`='1'");
} else {
    // Jika data belum ada, lakukan INSERT
    $insert_time_fs_adm = $server->query("INSERT INTO `gratis_ongkir` (`id_gratis_ongkir`, `waktu_berakhir`) VALUES ('1', '$time_fs_ub')");
}

if ($update_time_fs_adm || $insert_time_fs_adm) {
    ?>
    <script>
        window.location.href = 'index.php';
    </script>
    <?php
}
?>
