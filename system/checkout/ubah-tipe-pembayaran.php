<?php
include '../../config.php';

$id_invoice_tp = $_POST['id_invoice_tp'];
$v_tp = $_POST['v_tp'];

$update_tp = $server->query("UPDATE `invoice` SET `tipe_pembayaran`='$v_tp' WHERE `idinvoice`='$id_invoice_tp'");

if ($update_tp) {
    ?>
    <script>
        window.location.href = '';
    </script>
    <?php
}