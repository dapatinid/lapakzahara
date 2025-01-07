<?php
include '../../config.php';

$idivcat = $_POST['idivcat'];
$i_catatanb = $_POST['i_catatanb'];

$upcatd = $server->query("UPDATE `invoice` SET `catatan`='$i_catatanb' WHERE `idinvoice`='$idivcat' ");

if ($upcatd) {
?>
    <script>
        window.location.href = '';
    </script>
<?php
}
?>