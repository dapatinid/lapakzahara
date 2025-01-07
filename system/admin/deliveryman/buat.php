<?php
include '../../../config.php';

$nama_sjdhgfjhs = $_POST['nama_sjdhgfjhs'];
$id_user_bbbj = $_POST['id_user_bbbj'];

if ($id_user_bbbj == '') {
    $bujnsgf = $server->query("INSERT INTO `setting_kurir_toko_orang`(`nama`) VALUES ('$nama_sjdhgfjhs')");
}else{
    $bujnsgf = $server->query("UPDATE `setting_kurir_toko_orang` SET `nama`='$nama_sjdhgfjhs' WHERE `id`='$id_user_bbbj' ");
}

if ($bujnsgf) {
    ?>
    <script>
        window.location.href = '../../../admin/deliveryman/';
    </script>
    <?php
}