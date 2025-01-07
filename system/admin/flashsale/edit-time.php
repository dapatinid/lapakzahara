<?php
include '../../../config.php';

$time_fs_ub = $_POST['time_fs_ub'];
$up_time_fs_ub = strtotime($time_fs_ub);

// Periksa apakah ada data flashsale dengan id_fs=1
$check_flashsale = $server->query("SELECT * FROM `flashsale` WHERE `id_fs`='1'");
if ($check_flashsale && $check_flashsale->num_rows > 0) {
    // Jika data sudah ada, lakukan UPDATE
    $update_time_fs_adm = $server->query("UPDATE `flashsale` SET `waktu_berakhir`='$up_time_fs_ub' WHERE `id_fs`='1'");
} else {
    // Jika data belum ada, lakukan INSERT
    $insert_time_fs_adm = $server->query("INSERT INTO `flashsale` (`id_fs`, `waktu_berakhir`) VALUES ('1', '$up_time_fs_ub')");
}

if ($update_time_fs_adm || $insert_time_fs_adm) {
    ?>
    <script>
        window.location.href = 'index.php';
    </script>
    <?php
}
?>
 