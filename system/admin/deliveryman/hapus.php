<?php
include '../../../config.php';

$iddvmsd = $_POST['iddvmsd'];

$delsdjf = $server->query("DELETE FROM `setting_kurir_toko_orang` WHERE `id`='$iddvmsd' ");

if ($delsdjf) {
?>
    <script>
        window.location.href = '../../admin/deliveryman/';
    </script>
<?php
}
